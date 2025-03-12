<?php

namespace App\Http\Controllers;

use App\Jobs\AnalyzeProposal;
use App\Models\Proposal;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use PDF;
use Symfony\Component\Process\Process;

class ProposalController extends Controller
{
    public function index()
    {
        if (Auth::user()->role === 'Admin') {
            return $this->admin(request());
        }

        $proposals = Proposal::where('user_id', Auth::id())
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('proposals.index', compact('proposals'));
    }

    public function admin(Request $request)
    {
        if (Auth::user()->role !== 'Admin') {
            abort(403);
        }

        $query = Proposal::with('user')->orderBy('created_at', 'desc');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                })->orWhere('purpose', 'like', "%{$search}%");
            });
        }

        if ($adminStatus = $request->input('admin_status')) {
            $query->where('admin_status', $adminStatus);
        }

        if ($aiStatus = $request->input('status')) {
            $query->where('status', $aiStatus);
        }

        $proposals = $query->paginate(10);

        return view('proposals.admin', compact('proposals'));
    }

    // public function index()
    // {
    //     $proposals = Proposal::with('user')->latest()->get();
    //     $scoredProposals = $proposals->map(function ($proposal) {
    //         $score = 0;
    //         // Custom AI scoring logic (e.g., call an AI service)
    //         if ($proposal->pricing) $score += (1000 - min(floatval(str_replace('$', '', $proposal->pricing)), 1000)) / 10;
    //         if ($proposal->delivery_timeline) $score += (str_contains(strtolower($proposal->delivery_timeline), 'march') ? 30 : 20);
    //         if ($proposal->valid_until && Carbon::parse($proposal->valid_until)->gt(now())) $score += 20;
    //         $proposal->ai_score = min($score, 100);
    //         return $proposal;
    //     })->sortByDesc('ai_score')->values();

    //     return view('proposals.index', ['proposals' => $scoredProposals->paginate(10)]);
    // }


    public function store(Request $request)
    {
        try {
            $request->validate([
                'proposal_title' => 'required|string|max:255',
                'vendor_name' => 'nullable|string|max:255',
                'email' => 'nullable|email|max:255',
                'product_service_type' => 'nullable|string|in:service,product,both',
                'description' => 'nullable|string',
                'delivery_timeline' => 'nullable|string|max:255',
                'pricing' => 'nullable|string|max:255',
                'valid_until' => 'nullable|date',
            ]);

            $bidData = [
                'proposal_title' => $request->proposal_title,
                'vendor_name' => $request->vendor_name,
                'email' => Auth::user()->email,
                'pricing' => $request->pricing,
                'delivery_timeline' => $request->delivery_timeline,
                'valid_until' => $request->valid_until,
            ];

            $jsonBidData = json_encode($bidData);
            $scriptPath = base_path('scripts/analyze_bid.py');
            $command = ['python', $scriptPath, $jsonBidData];

            flash()->info('Executing command: ' . implode(' ', $command));

            $process = new Process($command);
            $process->run();

            if (!$process->isSuccessful()) {
                $errorOutput = $process->getErrorOutput();
                $exitCode = $process->getExitCode();
                flash()->error("Process failed. Exit Code: $exitCode, Error Output: $errorOutput, Output: " . $process->getOutput());
                return response()->json([
                    'error' => 'Failed to analyze bid: ' . ($errorOutput ?: 'Unknown error'),
                ], 500);
            }

            $output = $process->getOutput();
            flash()->info("Script Output: $output");

            $result = json_decode($output, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                flash()->error('JSON Decode Error: ' . json_last_error_msg() . ', Raw Output: ' . $output);
                return response()->json([
                    'error' => "Invalid JSON output from script: $output",
                ], 500);
            }

            if (isset($result['error'])) {
                return response()->json([
                    'error' => $result['error'],
                ], 400);
            }

            $proposal = Proposal::create([
                'user_id' => Auth::id(),
                'proposal_title' => $result['proposal_title'],
                'vendor_name' => $result['vendor_name'],
                'email' => $result['email'],
                'product_service_type' => $request->product_service_type,
                'pricing' => $result['pricing'],
                'delivery_timeline' => $result['delivery_timeline'],
                'valid_until' => $result['valid_until'],
                'ai_score' => $result['ai_score'],
                'is_fraud' => $result['is_fraud'],
                'notes' => json_encode($result['notes']),
            ]);

            return response()->json([
                'message' => 'Bid submitted successfully!',
                'proposal' => $proposal,
            ], 201);
        } catch (Exception $e) {
            flash()->error('Store method exception: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            return response()->json([
                'error' => 'Server error: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        $proposal = Proposal::findOrFail($id);
        $proposal->delete();

        flash()->success('Proposal has been deleted successfully');

        return redirect()->route("proposals.index");
    }
    public function approved()
    {
        $proposals = Proposal::where('user_id', Auth::id())
            ->where('admin_status', 'approved') // AI-approved proposals
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('proposals.admin', compact('proposals'));
    }

    public function preview($id)
    {
        $proposal = Proposal::findOrFail($id);
        if ($proposal->user_id !== Auth::id() && Auth::user()->role !== 'Admin') {
            abort(403, 'Unauthorized action.');
        }

        $filePath = $proposal->documentation_path;
        if (!Storage::disk('private')->exists($filePath)) {
            abort(404, 'File not found.');
        }

        return response()->file(Storage::disk('private')->path($filePath));
    }

    public function approve(Request $request, $id)
    {
        if (Auth::user()->role !== 'Admin') {
            abort(403);
        }

        $proposal = Proposal::findOrFail($id);
        $proposal->update([
            'admin_status' => 'approved', // Admin approval
            'actioned_by' => Auth::user()->name,
            'approved_by' => Auth::user()->id,
            'fraud_notes' => $request->input('notes') ?: $proposal->fraud_notes,
        ]);

        flash()->success("Proposal by {$proposal->user->name} has been approved");
        return response()->json(['success' => true]);
    }

    public function decline(Request $request, $id)
    {
        if (Auth::user()->role !== 'Admin') {
            abort(403);
        }

        $proposal = Proposal::findOrFail($id);
        $proposal->update([
            'admin_status' => 'rejected', // Admin rejection
            'actioned_by' => Auth::user()->name,
            'approved_by' => Auth::user()->id,
            'fraud_notes' => $request->input('notes') ?: $proposal->fraud_notes,
        ]);

        flash()->success("Proposal by {$proposal->user->name} has been rejected");
        return response()->json(['success' => true]);
    }

    public function generateCustomContract(Request $request)
    {
        $data = [
            'companyName' => 'Your Bus Company', // Replace with your actual company name
            'legalEntity' => 'Corporation', // Adjust as needed
            'jurisdiction' => 'Philippines', // Replace with your jurisdiction
            'companyAddress' => '123 Transport Lane, Manila, Philippines', // Your address
            'companyRepName' => 'Your Name', // Your name or default rep
            'companyRepTitle' => 'CEO', // Your title
            'companyContact' => 'info@yourbuscompany.com | +63 123 456 7890', // Your contact
            'date' => now()->format('Y-m-d'),

            // Vendor-specific fields (from request or defaults)
            'vendorName' => $request->input('vendor_name', 'Vendor Name'),
            'vendorAddress' => $request->input('vendor_address', 'Vendor Address'),
            'vendorTitle' => $request->input('vendor_title', 'Owner'),
            'vendorContact' => $request->input('vendor_contact', 'vendor@example.com'),

            // Contract details
            'serviceDescription' => $request->input('service_description', 'Daily passenger transport'),
            'serviceArea' => $request->input('service_area', 'Metro Manila routes'),
            'schedule' => $request->input('schedule', 'Monday to Friday, 6 AM - 6 PM'),
            'vehicles' => $request->input('vehicles', '2 buses, minimum 40-seat capacity each'),
            'startDate' => $request->input('start_date', now()->format('Y-m-d')),
            'endDate' => $request->input('end_date', now()->addYear()->format('Y-m-d')),
            'rate' => $request->input('rate', '$500 per bus per month'),
            'invoiceSchedule' => $request->input('invoice_schedule', 'Monthly, due by the 5th'),
            'paymentDue' => $request->input('payment_due', 'Within 15 days of invoice receipt'),
            'paymentMethod' => $request->input('payment_method', 'Bank transfer'),
            'noticeDays' => $request->input('notice_days', '7'),
            'performanceThreshold' => $request->input('performance_threshold', '95%'),
            'penalty' => $request->input('penalty', '10% of monthly rate per incident'),
            'terminationNotice' => $request->input('termination_notice', '30'),
            'insurance' => $request->input('insurance', '$1M liability coverage per vehicle'),
            'clarificationDays' => $request->input('clarification_days', '5'),
        ];

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('contracts.custom_template', $data);
        return $pdf->download('bus_service_vendor_proposal.pdf');
    }
}
