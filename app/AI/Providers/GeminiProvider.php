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

        $systemInstruction = ($context === 'json' || $context === 'raw')
            ? 'You are an expert AI assistant and educational assessment specialist. Always output valid JSON strictly as requested.'
            : RoboBotPrompt::systemPrompt($user, $context);

        $url = "https://generativelanguage.googleapis.com/v1beta/models/{$this->model}:generateContent?key={$this->apiKey}";

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
        ];

        if ($context === 'json') {
            $payload['generationConfig'] = [
                'responseMimeType' => 'application/json',
            ];
        }

        try {
            $response = Http::timeout(45)->post($url, $payload);

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
