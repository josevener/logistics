<?php

namespace App\Http\Controllers;

use App\Jobs\AnalyzeProposal;
use App\Models\Proposal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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

    public function store(Request $request)
    {
        $request->validate([
            'company_info' => 'required|string|max:255',
            'contact_details' => 'required|string|max:255',
            'documentation' => 'required|file|mimes:pdf,doc,docx|max:2048',
        ]);

        $filePath = $request->file('documentation')->store('proposals', 'private');

        $proposal = Proposal::create([
            'user_id' => Auth::id(),
            'company_info' => $request->company_info,
            'contact_details' => $request->contact_details,
            'purpose' => $request->company_info,
            'documentation_path' => $filePath,
            'status' => 'pending', // AI status
        ]);

        AnalyzeProposal::dispatch($proposal);

        flash()->success('Proposal submitted successfully!');
        return redirect()->route('proposals.index');
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
}
