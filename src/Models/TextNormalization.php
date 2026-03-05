<?php

namespace Tigusigalpa\YandexSpeechKit\Models;

class TextNormalization
{
    public function __construct(
        public readonly string $textNormalization = 'TEXT_NORMALIZATION_ENABLED',
        public readonly bool $profanityFilter = false,
        public readonly bool $literatureText = false,
        public readonly string $phoneFormattingMode = 'PHONE_FORMATTING_MODE_DISABLED'
    ) {
    }

    public function toArray(): array
    {
        return [
            'textNormalization' => $this->textNormalization,
            'profanityFilter' => $this->profanityFilter,
            'literatureText' => $this->literatureText,
            'phoneFormattingMode' => $this->phoneFormattingMode,
        ];
    }
}
