<?php

namespace App\AI\Providers;

use App\AI\Contracts\AIProviderInterface;
use App\AI\DTOs\AIChatResponse;
use App\AI\Prompts\RoboBotPrompt;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OpenAIProvider implements AIProviderInterface
{
    protected string $apiKey;

    protected string $model;

    protected string $baseUrl;

    public function __construct(?string $apiKey = null, ?string $model = null, ?string $baseUrl = null)
    {
        $this->apiKey = $apiKey ?? config('services.openai.api_key', env('OPENAI_API_KEY', ''));
        $this->model = $model ?? config('services.openai.model', env('OPENAI_MODEL', 'gpt-4o-mini'));
        $this->baseUrl = $baseUrl ?? config('services.openai.base_url', env('OPENAI_BASE_URL', 'https://api.openai.com/v1'));
    }

    public function chat(string $message, ?User $user = null, ?string $context = null): AIChatResponse
    {
        if (empty($this->apiKey)) {
            Log::warning('OpenAI API key is not configured, falling back.');

            return $this->fallback($message, $user, $context);
        }

        $systemInstruction = match ($context) {
            'json' => 'You are an expert educational assessment specialist and mathematics teacher. You MUST always output valid pure JSON strictly following the requested structure, with no markdown code blocks and no surrounding text.',
            'raw' => 'You are an expert AI document summarizer and education assistant for RoboMath. Follow all formatting and summarization guidelines provided in the prompt accurately in Indonesian.',
            default => RoboBotPrompt::systemPrompt($user, $context),
        };

        $payload = [
            'model' => $this->model,
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

        if ($context === 'json') {
            $payload['response_format'] = ['type' => 'json_object'];
        }

        try {
            $endpoint = rtrim($this->baseUrl, '/').'/chat/completions';
            $timeout = $context === 'raw' ? 120 : 60;

            $attempts = 0;
            $response = null;

            while ($attempts < 3) {
                $attempts++;
                $response = Http::withToken($this->apiKey)
                    ->timeout($timeout)
                    ->post($endpoint, $payload);

                if ($response->successful()) {
                    break;
                }

                if (in_array($response->status(), [429, 500, 502, 503, 504]) && $attempts < 3) {
                    if ($response->status() === 429 && str_contains($response->body(), 'insufficient_quota')) {
                        Log::error('OpenAI API Error: Insufficient quota / no credits remaining.');
                        break;
                    }

                    Log::warning("OpenAI API Status {$response->status()}, retrying attempt {$attempts}...");
                    sleep(1);

                    continue;
                }

                break;
            }

            if ($response && $response->successful()) {
                $rawContent = $response->json('choices.0.message.content') ?? '';
                $cleanText = preg_replace('/<think>[\s\S]*?<\/think>/i', '', $rawContent);
                $cleanText = trim($cleanText);

                $tokensUsed = $response->json('usage.total_tokens');

                return new AIChatResponse(
                    text: $cleanText ?: $rawContent,
                    provider: 'openai',
                    tokensUsed: $tokensUsed
                );
            }

            Log::error('OpenAI API Error: '.($response ? $response->body() : 'No response'));
        } catch (\Throwable $e) {
            Log::error('OpenAI Provider Exception: '.$e->getMessage());
        }

        return $this->fallback($message, $user, $context);
    }

    /**
     * Fallback to Mock response.
     */
    protected function fallback(string $message, ?User $user = null, ?string $context = null): AIChatResponse
    {
        return (new MockProvider)->chat($message, $user, $context);
    }
}
