<?php

namespace Tigusigalpa\YandexSpeechKit\Models;

class LanguageRestriction
{
    public function __construct(
        public readonly string $restrictionType = 'WHITELIST',
        public readonly array $languageCode = ['ru-RU']
    ) {
    }

    public function toArray(): array
    {
        return [
            'restrictionType' => $this->restrictionType,
            'languageCode' => $this->languageCode,
        ];
    }
}
