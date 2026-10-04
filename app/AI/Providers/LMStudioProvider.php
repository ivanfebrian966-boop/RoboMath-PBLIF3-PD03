<?php

namespace App\AI\Providers;

use App\AI\Contracts\AIProviderInterface;
use App\AI\DTOs\AIChatResponse;
use App\AI\Prompts\RoboBotPrompt;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class LMStudioProvider implements AIProviderInterface
{
    protected string $baseUrl;

    protected ?string $model;

    public function __construct(?string $baseUrl = null, ?string $model = null)
    {
        $url = $baseUrl ?? config('services.lmstudio.base_url', env('LMSTUDIO_BASE_URL', 'http://127.0.0.1:1234/v1'));
        $this->baseUrl = rtrim($url, '/');
        $this->model = $model ?? config('services.lmstudio.model', env('LMSTUDIO_MODEL', null));
    }

    public function chat(string $message, ?User $user = null, ?string $context = null): AIChatResponse
    {
        $systemInstruction = match ($context) {
            'json' => 'You are an expert educational assessment specialist and mathematics teacher. You MUST always output valid pure JSON strictly following the requested structure, with no markdown code blocks and no surrounding text.',
            'raw' => 'You are an expert AI document summarizer and education assistant for RoboMath. Follow all formatting and summarization guidelines provided in the prompt accurately in Indonesian.',
            default => RoboBotPrompt::systemPrompt($user, $context),
        };

        $payload = [
            'messages' => [
                [
                    'role' => 'system',
                    'content' => $systemInstruction,
                ],
                [
                    'role' => 'user',
                    'content' => $message,
                ],
            ],
            'temperature' => $context === 'json' ? 0.2 : 0.4,
            'max_tokens' => $context === 'raw' ? 4000 : 1500,
        ];

        if (! empty($this->model)) {
            $payload['model'] = $this->model;
        }

        if ($context === 'json') {
            $payload['response_format'] = ['type' => 'json_object'];
        }

        try {
            $endpoint = str_ends_with($this->baseUrl, '/v1')
                ? "{$this->baseUrl}/chat/completions"
                : "{$this->baseUrl}/v1/chat/completions";

            // Use longer timeouts: connect 10s (model loading), request up to 3 min for long docs
            $requestTimeout = $context === 'raw' ? 180 : 90;
            $response = Http::connectTimeout(10)->timeout($requestTimeout)->post($endpoint, $payload);

            if ($response->successful()) {
                $rawContent = $response->json('choices.0.message.content') ?? '';

                // Clean any reasoning <think>...</think> tags if present
                $cleanText = preg_replace('/<think>[\s\S]*?<\/think>/i', '', $rawContent);
                $cleanText = trim($cleanText);

                $tokensUsed = $response->json('usage.total_tokens');

                return new AIChatResponse(
                    text: $cleanText ?: $rawContent,
                    provider: 'lmstudio',
                    tokensUsed: $tokensUsed
                );
            }

            Log::error("LM Studio API Error ({$response->status()}) on {$endpoint}: ".$response->body());
        } catch (\Throwable $e) {
            Log::error('LM Studio Provider Exception ['.$this->baseUrl.']: '.$e->getMessage());
        }

        // Fallback: try Gemini if configured, otherwise use Mock
        $geminiKey = config('services.gemini.api_key', env('GEMINI_API_KEY'));
        if (! empty($geminiKey)) {
            try {
                return (new GeminiProvider)->chat($message, $user, $context);
            } catch (\Throwable $e) {
                Log::error('LM Studio fallback to Gemini also failed: '.$e->getMessage());
            }
        }

        return (new MockProvider)->chat($message, $user, $context);
    }
}
