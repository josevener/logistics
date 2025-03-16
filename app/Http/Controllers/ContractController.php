<?php

namespace App\Http\Controllers;

use App\Jobs\AnalyzeContract;
use App\Models\Contract;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Process\Process;

class ContractController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::user()->role === 'Admin') {
            return $this->admin(request());
        }

        $contracts = Contract::where('vendor_id', Auth::user()->id)
            ->with('vendor')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('contracts.index', compact('contracts'));
    }

    public function admin(Request $request)
    {
        $query = Contract::with('vendor.user');

        // Search
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('vendor.user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                })->orWhere('purpose', 'like', "%{$search}%");
            });
        }

        // Filter by admin_status
        if ($adminStatus = $request->input('admin_status')) {
            $query->where('admin_status', $adminStatus);
        }

        // Filter by ai_status
        if ($aiStatus = $request->input('status')) {
            $query->where('status', $aiStatus);
        }

        // Paginate (10 items per page)
        $contracts = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('contracts.admin', compact('contracts'));
    }

    // Approve contract
    public function approve($id, Request $request)
    {
        $contract = Contract::findOrFail($id);
        // $existingNotes = $contract->fraud_notes ? $contract->fraud_notes . ' | ' : '';
        $adminNote = $request->input('notes', 'Contract approved by ' . Auth::user()->name);
        $contract->update([
            'admin_status' => 'Approved',
            'approved_by' => Auth::user()->id,
            'actioned_by' => Auth::user()->name,
            'admin_notes' => $adminNote,
        ]);

        flash()->success('Contract has been approved');

        return response()->json(['success' => true]);
    }

    // Decline contract
    public function decline($id, Request $request)
    {
        $contract = Contract::findOrFail($id);
        // $existingNotes = $contract->fraud_notes ? $contract->fraud_notes . ' | ' : '';
        $adminNote = $request->input('notes', 'Contract rejected by ' . Auth::user()->name);
        $contract->update([
            'admin_status' => 'Rejected',
            'approved_by' => Auth::user()->id,
            'actioned_by' => Auth::user()->name,
            'admin_notes' => $adminNote,
        ]);

        flash()->success('Contract has been rejected');

        return response()->json(['success' => true]);
    }

    public function preview($id)
    {
        $contract = Contract::findOrFail($id);
        $filePath = $contract->file_path;
        $fullPath = storage_path('app/public/' . $filePath);

        if (!file_exists($fullPath)) {
            abort(404, 'File not found.');
        }

        $stream = fopen($fullPath, 'r');

        return new StreamedResponse(function () use ($stream) {
            fpassthru($stream);
            fclose($stream);
        }, 200, [
            'Content-Type' => mime_content_type($fullPath),
            'Content-Disposition' => 'inline; filename="' . basename($filePath) . '"',
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('contracts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'contract' => 'required|file|mimes:pdf,jpg,png|max:2048',
                'purpose' => 'required|string|max:255',
            ]);

            // Store the uploaded file
            $path = $request->file('contract')->store('contracts', 'public');
            $filePath = storage_path('app/public/' . $path);

            $contract = Contract::create([
                'vendor_id' => Auth::user()->id,

                'file_path' => $path,
                'purpose' => $request->purpose,
            ]);

            // AnalyzeContract::dispatch($contract);

            // Run the Python script synchronously
            $scriptPath = base_path('scripts/analyze_contract.py');
            $command = ['python3', $scriptPath, $filePath]; // Use 'python3' for Linux compatibility

            $process = new Process($command);
            $process->run();

            if (!$process->isSuccessful()) {
                $errorOutput = $process->getErrorOutput();
                $exitCode = $process->getExitCode();
                flash()->error("Process failed. Exit Code: $exitCode, Error Output: $errorOutput, Output: " . $process->getOutput());

                $contract->update([
                    'status' => 'error',
                    'fraud_notes' => 'Analysis failed: ' . ($errorOutput ?: 'Unknown error'),
                ]);

                return redirect()->route('contracts.index')->with('error', 'Failed to analyze contract');
            }
            $output = $process->getOutput();
            flash()->info("Script Output: $output");

            $result = json_decode($output, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                flash()->error('JSON Decode Error: ' . json_last_error_msg() . ', Raw Output: ' . $output);
                $contract->update([
                    'status' => 'error',
                    'fraud_notes' => 'Invalid JSON output from script: ' . $output,
                ]);
                return redirect()->route('contracts.index')->with('error', 'Invalid analysis output');
            }

            // Update the contract with analysis results
            $contract->update([
                'status' => $result['is_fraud'] ? 'flagged' : 'approved',
                'fraud_notes' => $result['notes'] ?? null,
                // Add any additional fields from your analyze_contract.py output if needed
            ]);

            flash()->success('Contract uploaded successfully');

            return redirect()->route('contracts.index');
        } catch (Exception $e) {
            flash()->error('Failed to upload contract: ' . $e->getMessage());

            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Contract $contract)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Contract $contract)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Contract $contract)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contract $contract)
    {
        //
    }


    public function uploadForm()
    {
        return view('contracts.upload');
    }
}
