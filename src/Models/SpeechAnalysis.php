<?php

namespace Tigusigalpa\YandexSpeechKit\Models;

class SpeechAnalysis
{
    public function __construct(
        public readonly bool $enableSpeakerAnalysis = false,
        public readonly bool $enableConversationAnalysis = false,
        public readonly array $descriptiveStatisticsQuantiles = []
    ) {
    }

    public function toArray(): array
    {
        return [
            'enableSpeakerAnalysis' => $this->enableSpeakerAnalysis,
            'enableConversationAnalysis' => $this->enableConversationAnalysis,
            'descriptiveStatisticsQuantiles' => $this->descriptiveStatisticsQuantiles,
        ];
    }
}
