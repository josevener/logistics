<?php

namespace App\Http\Controllers;

use App\Models\Compliance;
use App\Models\ComplianceDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ComplianceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('compliance.index');
    }


    public function upload(Request $request)
    {
        $validated = $request->validate([
            'files.*' => 'required|mimes:pdf|max:10240', // 10MB max per file
            'files' => 'max:10', // Limit total files to 10
        ]);

        try {
            // Create a single Compliance record for this batch of files
            $vendorCompliance = Compliance::create([
                'vendor_id' => Auth::id(),
                'requirement' => 'General Compliance',
                'status' => 'pending',
            ]);

            $documents = []; // To return created document details
            foreach ($request->file('files') as $file) {
                $path = $file->store('compliance_docs', 'public');

                $document = ComplianceDocument::create([
                    'vendor_compliance_id' => $vendorCompliance->id,
                    'document_path' => $path,
                    'file_name' => $file->getClientOriginalName(),
                    'file_size' => $file->getSize(), // In bytes
                    'file_type' => $file->getMimeType(),
                ]);

                $documents[] = [
                    'id' => $document->id,
                    'file_name' => $document->file_name,
                    'document_path' => $document->document_path,
                ];
            }

            return response()->json([
                'success' => true,
                'vendor_compliance_id' => $vendorCompliance->id,
                'documents' => $documents,
            ]);
        } catch (\Exception $e) {
            // Optionally log the error for debugging
            // \Log::error('Upload failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'error' => 'Upload failed. Please try again.',
                'details' => $e->getMessage(), // Optional: Remove in production
            ], 500);
        }
    }


    public function list()
    {
        // Fetch compliance records with all related documents
        $compliances = Compliance::with('documents')->latest()->get();
        return response()->json($compliances);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Compliance $compliance)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Compliance $compliance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Compliance $compliance)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Compliance $compliance)
    {
        //
    }
}
