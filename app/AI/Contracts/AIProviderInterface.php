<?php

namespace App\AI\Contracts;

use App\AI\DTOs\AIChatResponse;
use App\Models\User;

interface AIProviderInterface
{
    /**
     * Send a chat message and receive AI response.
     */
    public function chat(string $message, ?User $user = null, ?string $context = null): AIChatResponse;

    /**
     * Send a prompt together with a file (PDF or image) encoded as base64.
     * Providers that do not support vision should fall back to text-only chat.
     *
     * @param  string  $mimeType  e.g. 'application/pdf', 'image/png'
     */
    public function chatWithDocument(string $message, string $base64Data, string $mimeType, ?User $user = null, ?string $context = null): AIChatResponse;
}
