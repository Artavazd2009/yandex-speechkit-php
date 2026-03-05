<?php

namespace Tigusigalpa\YandexSpeechKit\Models;

class SpeakerLabeling
{
    public function __construct(
        public readonly string $speakerLabeling = 'SPEAKER_LABELING_ENABLED'
    ) {
    }

    public function toArray(): array
    {
        return [
            'speakerLabeling' => $this->speakerLabeling,
        ];
    }
}
