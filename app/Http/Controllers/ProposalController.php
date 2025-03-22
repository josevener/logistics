<?php

namespace App\Http\Controllers;

use App\Models\Proposal;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Phpml\Regression\SVR;
use Phpml\SupportVectorMachine\Kernel;
use Phpml\Classification\Linear\LogisticRegression;
use Phpml\ModelManager;
use Carbon\Carbon;

class ProposalController extends Controller
{
    private ?SVR $scoreModel = null;
    private ?LogisticRegression $fraudModel = null;

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

        $query = Proposal::with('user');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('proposal_title', 'like', "%{$search}%")
                    ->orWhere('vendor_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('purpose', 'like', "%{$search}%");
            });
        }

        if ($adminStatus = $request->input('admin_status')) {
            $query->where('admin_status', $adminStatus);
        }

        if ($aiStatus = $request->input('status')) {
            $query->where('status', $aiStatus);
        }

        $proposals = $query->orderBy('proposal_evaluation_score', 'desc')->paginate(10);

        return view('proposals.admin', compact('proposals')); // Assuming admin uses same view
    }

    public function show($id)
    {
        $proposal = Proposal::with('user')->findOrFail($id);
        return response()->json($proposal);
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'proposal_title' => 'required|string|max:255',
                'vendor_name' => 'nullable|string|max:255',
                'email' => 'nullable|email|max:255',
                'product_service_type' => 'required|string|in:service,product,both', // Made required
                'description' => 'nullable|string',
                'delivery_timeline' => 'required|date|after_or_equal:today', // Fixed to date
                'pricing' => 'required|numeric', // Fixed pricing
                'valid_until' => 'required|date|after_or_equal:delivery_timeline', // Fixed to logical date
            ]);

            $proposalData = [
                'user_id' => Auth::id(),
                'proposal_title' => $request->proposal_title,
                'vendor_name' => $request->vendor_name,
                'email' => Auth::user()->email,
                'product_service_type' => $request->product_service_type,
                'description' => $request->description,
                'pricing' => $request->pricing,
                'delivery_timeline' => $request->delivery_timeline,
                'valid_until' => $request->valid_until,
            ];

            $proposal = new Proposal($proposalData);

            $this->loadOrTrainModels();
            $features = $this->extractFeatures($proposal);
            $score = $this->scoreModel->predict($features['data']);
            $score = max(0, min(100, $score));
            $isFraud = $this->fraudModel->predict($features['data']);

            $notes = json_encode([
                'scoring' => $features['notes'],
                'score_interpretation' => $this->getScoreInterpretation($score), // Added text-equivalent
                'fraud' => $isFraud ? ['Likely fraudulent based on ML analysis'] : ['Appears legitimate per ML analysis'],
                'features_used' => [
                    'price' => $features['data'][0],
                    'delivery_days' => $features['data'][1],
                    'valid_days' => $features['data'][2],
                ],
            ]);

            $proposal->proposal_evaluation_score = round($score, 2); // Updated column name
            $proposal->is_fraud = (bool) $isFraud;
            $proposal->notes = $notes;
            $proposal->admin_status = $isFraud ? 'flagged' : 'pending'; // Changed default to 'pending'

            $proposal->save();

            flash()->success('Bid submitted successfully');

            return response()->json([
                'message' => 'Bid submitted successfully!',
                'proposal' => $proposal,
            ], 201);
        } catch (Exception $e) {
            Log::error("Failed to store proposal", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            flash()->error('Store method exception: ' . $e->getMessage());
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
            ->where('admin_status', 'approved')
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('proposals.index', compact('proposals')); // Updated view name
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
            'admin_status' => 'approved',
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
            'admin_status' => 'rejected',
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
            'companyName' => 'Your Bus Company',
            'legalEntity' => 'Corporation',
            'jurisdiction' => 'Philippines',
            'companyAddress' => '123 Transport Lane, Manila, Philippines',
            'companyRepName' => 'Your Name',
            'companyRepTitle' => 'CEO',
            'companyContact' => 'info@yourbuscompany.com | +63 123 456 7890',
            'date' => now()->format('Y-m-d'),
            'vendorName' => $request->input('vendor_name', 'Vendor Name'),
            'vendorAddress' => $request->input('vendor_address', 'Vendor Address'),
            'vendorTitle' => $request->input('vendor_title', 'Owner'),
            'vendorContact' => $request->input('vendor_contact', 'vendor@example.com'),
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

    private function loadOrTrainModels(): void
    {
        $modelManager = new ModelManager();
        $scoreModelPath = storage_path('app/models/score_model.phpml');
        $fraudModelPath = storage_path('app/models/fraud_model.phpml');

        $modelsDir = storage_path('app/models');
        if (!file_exists($modelsDir)) {
            mkdir($modelsDir, 0775, true);
            Log::info("Created models directory at {$modelsDir}");
        }

        if (file_exists($scoreModelPath) && file_exists($fraudModelPath)) {
            $scoreModel = $modelManager->restoreFromFile($scoreModelPath);
            $fraudModel = $modelManager->restoreFromFile($fraudModelPath);

            if ($scoreModel instanceof SVR) {
                $this->scoreModel = $scoreModel;
            } else {
                throw new Exception("Loaded score model is not an SVR instance.");
            }

            if ($fraudModel instanceof LogisticRegression) {
                $this->fraudModel = $fraudModel;
            } else {
                throw new Exception("Loaded fraud model is not a LogisticRegression instance.");
            }

            Log::info('Loaded existing AI models');
            return;
        }

        $trainingData = $this->getTrainingData();
        if (empty($trainingData)) {
            throw new Exception('No training data available to train models.');
        }

        $X = array_map(function ($bid) {
            return [
                $this->normalizeFeature($bid['pricing'], 1, 100000000),
                $this->normalizeFeature($bid['delivery_days'], 0, 360),
                $this->normalizeFeature($bid['valid_days'], 0, 360),
            ];
        }, $trainingData);

        $yScore = array_column($trainingData, 'score');
        $yFraud = array_column($trainingData, 'is_fraud');

        $this->scoreModel = new SVR(Kernel::LINEAR);
        $this->scoreModel->train($X, $yScore);
        $modelManager->saveToFile($this->scoreModel, $scoreModelPath);
        Log::info("Saved score model to {$scoreModelPath}");

        $this->fraudModel = new LogisticRegression();
        $this->fraudModel->train($X, $yFraud);
        $modelManager->saveToFile($this->fraudModel, $fraudModelPath);
        Log::info("Saved fraud model to {$fraudModelPath}");

        Log::info('Trained and saved new AI models', ['training_samples' => count($trainingData)]);
    }

    private function getTrainingData(): array
    {
        $proposals = Proposal::whereNotNull('proposal_evaluation_score') // Updated column name
            ->whereNotNull('is_fraud')
            ->get(['pricing', 'delivery_timeline', 'valid_until', 'proposal_evaluation_score', 'is_fraud'])
            ->toArray();

        if (empty($proposals)) {
            Log::warning('No historical data found, using fallback training data.');
            return $this->getFallbackTrainingData();
        }

        $now = Carbon::now();
        return array_map(function ($proposal) use ($now) {
            $price = (float) str_replace(['₱', ','], '', $proposal['pricing'] ?? '₱0');
            $deliveryDays = $proposal['delivery_timeline']
                ? $now->diffInDays(Carbon::parse($proposal['delivery_timeline']), false)
                : 0;
            $validDays = $proposal['valid_until']
                ? $now->diffInDays(Carbon::parse($proposal['valid_until']), false)
                : 0;

            return [
                'pricing' => $price,
                'delivery_days' => max(0, $deliveryDays),
                'valid_days' => max(0, $validDays),
                'score' => $proposal['proposal_evaluation_score'], // Updated column name
                'is_fraud' => (int) $proposal['is_fraud'],
            ];
        }, $proposals);
    }

    private function getFallbackTrainingData(): array
    {
        return [
            ['pricing' => '₱50000', 'delivery_days' => 30, 'valid_days' => 60, 'score' => 85, 'is_fraud' => 0],
            ['pricing' => '₱2000000', 'delivery_days' => 180, 'valid_days' => 10, 'score' => 40, 'is_fraud' => 1],
            ['pricing' => '₱100000', 'delivery_days' => 60, 'valid_days' => 90, 'score' => 75, 'is_fraud' => 0],
            ['pricing' => '₱25000', 'delivery_days' => 15, 'valid_days' => 120, 'score' => 90, 'is_fraud' => 0],
            ['pricing' => '₱1500000', 'delivery_days' => 240, 'valid_days' => 30, 'score' => 50, 'is_fraud' => 0],
            ['pricing' => '₱75000', 'delivery_days' => 45, 'valid_days' => 45, 'score' => 80, 'is_fraud' => 0],
            ['pricing' => '₱5000000', 'delivery_days' => 90, 'valid_days' => 5, 'score' => 30, 'is_fraud' => 1],
            ['pricing' => '₱200000', 'delivery_days' => 120, 'valid_days' => 60, 'score' => 65, 'is_fraud' => 0],
            ['pricing' => '₱30000', 'delivery_days' => 20, 'valid_days' => 180, 'score' => 92, 'is_fraud' => 0],
            ['pricing' => '₱800000', 'delivery_days' => 300, 'valid_days' => 15, 'score' => 45, 'is_fraud' => 1],
        ];
    }

    private function extractFeatures(Proposal $proposal): array
    {
        $now = Carbon::now();
        $notes = [];

        $price = 0.0;
        if ($proposal->pricing) {
            try {
                $price = (float) str_replace(['₱', ','], '', $proposal->pricing);
                if ($price < 0) {
                    $notes[] = 'Pricing is negative.';
                    $price = 0.0;
                } elseif ($price < 10000 || $price > 10000000) {
                    $notes[] = "Pricing ₱{$price} is outside typical range (₱10000-₱10000000).";
                }
            } catch (Exception $e) {
                $notes[] = 'Invalid pricing format.';
            }
        } else {
            $notes[] = 'Pricing missing.';
        }

        $deliveryDays = 0;
        if ($proposal->delivery_timeline) {
            try {
                $deliveryDate = Carbon::parse($proposal->delivery_timeline);
                $deliveryDays = max(0, $now->diffInDays($deliveryDate, false));
                if ($deliveryDays < 0) $notes[] = 'Delivery timeline is in the past.';
                elseif ($deliveryDays > 360) $notes[] = 'Delivery timeline exceeds 1 year.';
            } catch (Exception $e) {
                $notes[] = 'Invalid delivery timeline format.';
            }
        } else {
            $notes[] = 'Delivery timeline missing.';
        }

        $validDays = 0;
        if ($proposal->valid_until) {
            try {
                $validDate = Carbon::parse($proposal->valid_until);
                $validDays = max(0, $now->diffInDays($validDate, false));
                if ($validDays < 0) $notes[] = 'Proposal has expired.';
                elseif ($validDays < 5) $notes[] = 'Validity period is unusually short.';
            } catch (Exception $e) {
                $notes[] = 'Invalid valid_until format.';
            }
        } else {
            $notes[] = 'Valid until missing.';
        }

        $normalizedData = [
            $this->normalizeFeature($price, 10000, 10000000),
            $this->normalizeFeature($deliveryDays, 0, 360),
            $this->normalizeFeature($validDays, 0, 180),
        ];

        Log::info('Extracted Features', [
            'proposal_id' => $proposal->id ?? 'new',
            'price' => $price,
            'delivery_days' => $deliveryDays,
            'valid_days' => $validDays,
            'normalized' => $normalizedData,
        ]);

        return [
            'data' => $normalizedData,
            'notes' => $notes,
        ];
    }

    private function normalizeFeature(float $value, float $min, float $max): float
    {
        if ($max === $min) return 0.5;
        $normalized = ($value - $min) / ($max - $min);
        return max(0, min(1, $normalized));
    }

    private function getScoreInterpretation(float $score): string
    {
        if ($score >= 90) return "Excellent - Highly competitive proposal.";
        if ($score >= 80) return "Good - Strong proposal with minor areas for improvement.";
        if ($score >= 70) return "Fair - Acceptable but could be improved.";
        if ($score >= 50) return "Poor - Significant issues detected.";
        return "Very Poor - Major concerns with proposal viability.";
    }
}
