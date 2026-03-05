<?php

namespace Tigusigalpa\YandexSpeechKit\Models;

class AudioFormat
{
    private function __construct(
        private readonly ?array $rawAudio = null,
        private readonly ?array $containerAudio = null
    ) {
    }

    public static function container(string $containerAudioType): self
    {
        return new self(
            containerAudio: [
                'containerAudioType' => $containerAudioType,
            ]
        );
    }

    public static function raw(
        string $audioEncoding = 'LINEAR16_PCM',
        int $sampleRateHertz = 16000,
        int $audioChannelCount = 1
    ): self {
        return new self(
            rawAudio: [
                'audioEncoding' => $audioEncoding,
                'sampleRateHertz' => (string) $sampleRateHertz,
                'audioChannelCount' => (string) $audioChannelCount,
            ]
        );
    }

    public function toArray(): array
    {
        if ($this->rawAudio !== null) {
            return ['rawAudio' => $this->rawAudio];
        }

        if ($this->containerAudio !== null) {
            return ['containerAudio' => $this->containerAudio];
        }

        return [];
    }
}
