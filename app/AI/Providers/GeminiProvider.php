<?php

namespace App\AI\Providers;

use App\AI\Contracts\AIProviderInterface;
use App\AI\DTOs\AIChatResponse;
use App\AI\Prompts\RoboBotPrompt;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiProvider implements AIProviderInterface
{
    protected string $apiKey;

    protected string $model;

    public function __construct(?string $apiKey = null, ?string $model = null)
    {
        $this->apiKey = $apiKey ?? config('services.gemini.api_key', env('GEMINI_API_KEY', ''));
        $this->model = $model ?? config('services.gemini.model', 'gemini-flash-latest');
    }

    public function chat(string $message, ?User $user = null, ?string $context = null): AIChatResponse
    {
        if (empty($this->apiKey)) {
            Log::warning('Gemini API key is not configured, falling back to mock response.');

            return (new MockProvider)->chat($message, $user, $context);
        }

        $systemInstruction = match ($context) {
            'json' => 'You are an expert AI assistant and educational assessment specialist. Always output valid JSON strictly as requested.',
            'raw' => 'You are an expert AI document summarizer and education assistant for RoboMath. Follow all formatting and summarization guidelines provided in the prompt accurately in Indonesian.',
            default => RoboBotPrompt::systemPrompt($user, $context),
        };

        $url = "https://generativelanguage.googleapis.com/v1beta/models/{$this->model}:generateContent?key={$this->apiKey}";

        $generationConfig = [
            'temperature' => $context === 'json' ? 0.2 : 0.4,
            'topP' => 0.8,
            'topK' => 40,
        ];

        if ($context === 'json') {
            $generationConfig['responseMimeType'] = 'application/json';
        }

        $payload = [
            'system_instruction' => [
                'parts' => [
                    ['text' => $systemInstruction],
                ],
            ],
            'contents' => [
                [
                    'role' => 'user',
                    'parts' => [
                        ['text' => $message],
                    ],
                ],
            ],
            'generationConfig' => $generationConfig,
        ];

        try {
            $attempts = 0;
            $response = null;

            while ($attempts < 3) {
                $attempts++;
                $response = Http::timeout(45)->post($url, $payload);

                if ($response->successful()) {
                    break;
                }

                // If transient high demand (503) or rate-limit (429), retry after a short delay
                if (in_array($response->status(), [429, 500, 502, 503, 504]) && $attempts < 3) {
                    Log::warning("Gemini API Status {$response->status()} (High Demand), retrying attempt {$attempts}...");
                    sleep(1);

                    continue;
                }

                break;
            }

            if ($response && $response->successful()) {
                $candidates = $response->json('candidates.0.content.parts.0.text');
                $usage = $response->json('usageMetadata.totalTokenCount');

                return new AIChatResponse(
                    text: $candidates ?? 'Maaf, RoboBot belum bisa menjawab pertanyaan ini sekarang.',
                    provider: 'gemini',
                    tokensUsed: $usage
                );
            }

            Log::error('Gemini API Error: '.($response ? $response->body() : 'No response'));
        } catch (\Throwable $e) {
            Log::error('Gemini Provider Exception: '.$e->getMessage());
        }

        return (new MockProvider)->chat($message, $user, $context);
    }
}
