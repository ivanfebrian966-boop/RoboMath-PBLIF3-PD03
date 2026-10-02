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
        $systemInstruction = ($context === 'json' || $context === 'raw')
            ? 'You are an expert educational assessment specialist and mathematics teacher. You MUST always output valid pure JSON strictly following the requested structure, with no markdown code blocks and no surrounding text.'
            : RoboBotPrompt::systemPrompt($user, $context);

        $payload = [
            'messages' => [
                [
                    'role' => 'system',
                    'content' => $systemInstruction,
                ],
                [
                    'role' => 'user',
                    'parts' => $message,
                    'content' => $message,
                ],
            ],
            'temperature' => ($context === 'json' || $context === 'raw') ? 0.2 : 0.4,
            'max_tokens' => 1500,
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

            $response = Http::timeout(90)->post($endpoint, $payload);

            if ($response->successful()) {
                $rawContent = $response->json('choices.0.message.content') ?? '';

                // Clean DeepSeek/reasoning <think>...</think> tags if present
                $cleanText = preg_replace('/<think>[\s\S]*?<\/think>/i', '', $rawContent);
                $cleanText = trim($cleanText);

                $tokensUsed = $response->json('usage.total_tokens');

                return new AIChatResponse(
                    text: $cleanText ?: $rawContent,
                    provider: 'lmstudio',
                    tokensUsed: $tokensUsed
                );
            }

            Log::error('LM Studio API Error ('.$response->status().'): '.$response->body());
        } catch (\Throwable $e) {
            Log::error('LM Studio Provider Exception: '.$e->getMessage());
        }

        return (new MockProvider)->chat($message, $user, $context);
    }
}
