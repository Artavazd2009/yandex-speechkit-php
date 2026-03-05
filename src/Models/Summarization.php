<?php

namespace Tigusigalpa\YandexSpeechKit\Models;

class Summarization
{
    public function __construct(
        public readonly string $modelUri,
        public readonly array $properties = []
    ) {
    }

    public function toArray(): array
    {
        return [
            'modelUri' => $this->modelUri,
            'properties' => $this->properties,
        ];
    }
}
