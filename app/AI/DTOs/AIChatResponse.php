<?php

namespace App\AI\DTOs;

class AIChatResponse
{
    public function __construct(
        public string $text,
        public string $provider = 'mock',
        public ?int $tokensUsed = null,
        public array $metadata = []
    ) {}

    public function toArray(): array
    {
        return [
            'text' => $this->text,
            'provider' => $this->provider,
            'tokens_used' => $this->tokensUsed,
            'metadata' => $this->metadata,
        ];
    }
}
