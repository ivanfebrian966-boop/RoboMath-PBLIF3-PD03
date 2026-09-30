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
        $this->model = $model ?? config('services.gemini.model', 'gemini-1.5-flash');
    }

    public function chat(string $message, ?User $user = null, ?string $context = null): AIChatResponse
    {
        if (empty($this->apiKey)) {
            Log::warning('Gemini API key is not configured, falling back to mock response.');

            return (new MockProvider)->chat($message, $user, $context);
        }

        $systemInstruction = RoboBotPrompt::systemPrompt($user, $context);
        $url = "https://generativelanguage.googleapis.com/v1beta/models/{$this->model}:generateContent?key={$this->apiKey}";

        try {
            $response = Http::timeout(30)->post($url, [
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
            ]);

            if ($response->successful()) {
                $candidates = $response->json('candidates.0.content.parts.0.text');
                $usage = $response->json('usageMetadata.totalTokenCount');

                return new AIChatResponse(
                    text: $candidates ?? 'Maaf, RoboBot belum bisa menjawab pertanyaan ini sekarang.',
                    provider: 'gemini',
                    tokensUsed: $usage
                );
            }

            Log::error('Gemini API Error: '.$response->body());
        } catch (\Throwable $e) {
            Log::error('Gemini Provider Exception: '.$e->getMessage());
        }

        return (new MockProvider)->chat($message, $user, $context);
    }
}
