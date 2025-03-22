<?php

namespace App\Jobs;

use App\Models\Proposal;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Phpml\Regression\SVR;
use Phpml\SupportVectorMachine\Kernel;
use Phpml\Classification\Linear\LogisticRegression;
use Phpml\ModelManager;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AnalyzeProposal implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Proposal $proposal;

    // Declare properties with types and nullability
    private ?SVR $scoreModel = null;
    private ?LogisticRegression $fraudModel = null;

    public function __construct(Proposal $proposal)
    {
        $this->proposal = $proposal;
    }

    public function handle(): void
    {
        try {
            // Load or train models
            $this->loadOrTrainModels();

            // Extract and normalize features
            $features = $this->extractFeatures();

            // Predict
            $score = $this->scoreModel->predict($features['data']);
            $score = max(0, min(100, $score));
            $isFraud = $this->fraudModel->predict($features['data']);

            // Compile detailed notes
            $notes = json_encode([
                'scoring' => $features['notes'],
                'fraud' => $isFraud ? ['Likely fraudulent based on ML analysis'] : ['Appears legitimate per ML analysis'],
                'features_used' => [
                    'price' => $features['data'][0],
                    'delivery_days' => $features['data'][1],
                    'valid_days' => $features['data'][2],
                ],
            ]);

            // Update proposal
            $this->proposal->update([
                'ai_score' => round($score, 2),
                'is_fraud' => (bool) $isFraud,
                'notes' => $notes,
                'admin_status' => $isFraud ? 'flagged' : 'approved',
            ]);

            Log::info("Proposal {$this->proposal->id} analyzed successfully", [
                'ai_score' => $score,
                'is_fraud' => $isFraud,
                'features' => $features['data'],
            ]);
        } catch (\Exception $e) {
            $this->proposal->update([
                'admin_status' => 'error',
                'notes' => json_encode(['error' => 'Analysis failed: ' . $e->getMessage()]),
            ]);
            Log::error("Failed to analyze proposal {$this->proposal->id}", ['error' => $e->getMessage()]);
            $this->fail($e);
        }
    }

    private function loadOrTrainModels(): void
    {
        $modelManager = new ModelManager();
        $scoreModelPath = storage_path('app/models/score_model.phpml');
        $fraudModelPath = storage_path('app/models/fraud_model.phpml');

        // Load existing models if available
        if (file_exists($scoreModelPath) && file_exists($fraudModelPath)) {
            $this->scoreModel = $modelManager->restoreFromFile($scoreModelPath) instanceof SVR
                ? $modelManager->restoreFromFile($scoreModelPath)
                : null;
            $restoredFraudModel = $modelManager->restoreFromFile($fraudModelPath);
            $this->fraudModel = $restoredFraudModel instanceof LogisticRegression ? $restoredFraudModel : null;
            Log::info('Loaded existing AI models');
            return;
        }

        // Fetch training data from database or use fallback
        $trainingData = $this->getTrainingData();
        if (empty($trainingData)) {
            throw new \Exception('No training data available to train models.');
        }

        // Prepare features (X) and labels (y)
        $X = array_map(function ($bid) {
            return [
                $this->normalizeFeature($bid['pricing'], 10000, 10000000), // Normalize price
                $this->normalizeFeature($bid['delivery_days'], 0, 360),   // Normalize delivery days
                $this->normalizeFeature($bid['valid_days'], 0, 180),      // Normalize valid days
            ];
        }, $trainingData);

        $yScore = array_column($trainingData, 'score');
        $yFraud = array_column($trainingData, 'is_fraud');

        // Train models
        $this->scoreModel = new SVR(Kernel::LINEAR);
        $this->scoreModel->train($X, $yScore);
        $modelManager->saveToFile($this->scoreModel, $scoreModelPath);

        $this->fraudModel = new LogisticRegression();
        $this->fraudModel->train($X, $yFraud);
        $modelManager->saveToFile($this->fraudModel, $fraudModelPath);

        Log::info('Trained and saved new AI models', ['training_samples' => count($trainingData)]);
    }

    private function getTrainingData(): array
    {
        // Fetch historical proposals with scores and fraud status
        $proposals = Proposal::whereNotNull('ai_score')
            ->whereNotNull('is_fraud')
            ->get(['pricing', 'delivery_timeline', 'valid_until', 'ai_score', 'is_fraud'])
            ->toArray();

        if (empty($proposals)) {
            // Fallback to hardcoded data if no historical data exists
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
                'score' => $proposal['ai_score'],
                'is_fraud' => (int) $proposal['is_fraud'],
            ];
        }, $proposals);
    }

    private function getFallbackTrainingData(): array
    {
        // Hardcoded fallback data if no database records exist
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

    private function extractFeatures(): array
    {
        $now = Carbon::now();
        $notes = [];

        // Pricing
        $price = 0.0;
        if ($this->proposal->pricing) {
            try {
                $price = (float) str_replace(['₱', ','], '', $this->proposal->pricing);
                if ($price < 0) {
                    $notes[] = 'Pricing is negative.';
                    $price = 0.0;
                } elseif ($price < 10000 || $price > 10000000) {
                    $notes[] = "Pricing ₱{$price} is outside typical range (₱10000-₱10000000).";
                }
            } catch (\Exception $e) {
                $notes[] = 'Invalid pricing format.';
            }
        } else {
            $notes[] = 'Pricing missing.';
        }

        // Delivery days
        $deliveryDays = 0;
        if ($this->proposal->delivery_timeline) {
            try {
                $deliveryDate = Carbon::parse($this->proposal->delivery_timeline);
                $deliveryDays = max(0, $now->diffInDays($deliveryDate, false));
                if ($deliveryDays < 0) $notes[] = 'Delivery timeline is in the past.';
                elseif ($deliveryDays > 360) $notes[] = 'Delivery timeline exceeds 1 year.';
            } catch (\Exception $e) {
                $notes[] = 'Invalid delivery timeline format.';
            }
        } else {
            $notes[] = 'Delivery timeline missing.';
        }

        // Valid days
        $validDays = 0;
        if ($this->proposal->valid_until) {
            try {
                $validDate = Carbon::parse($this->proposal->valid_until);
                $validDays = max(0, $now->diffInDays($validDate, false));
                if ($validDays < 0) $notes[] = 'Proposal has expired.';
                elseif ($validDays < 5) $notes[] = 'Validity period is unusually short.';
            } catch (\Exception $e) {
                $notes[] = 'Invalid valid_until format.';
            }
        } else {
            $notes[] = 'Valid until missing.';
        }

        // Normalize features for ML
        $normalizedData = [
            $this->normalizeFeature($price, 10000, 10000000),
            $this->normalizeFeature($deliveryDays, 0, 360),
            $this->normalizeFeature($validDays, 0, 180),
        ];

        return [
            'data' => $normalizedData,
            'notes' => $notes,
        ];
    }

    private function normalizeFeature(float $value, float $min, float $max): float
    {
        // Min-max normalization to scale values between 0 and 1
        if ($max === $min) return 0.5; // Avoid division by zero
        $normalized = ($value - $min) / ($max - $min);
        return max(0, min(1, $normalized)); // Clamp to 0-1 range
    }
}
