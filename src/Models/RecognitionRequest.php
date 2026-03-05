<?php

namespace Tigusigalpa\YandexSpeechKit\Models;

use InvalidArgumentException;

class RecognitionRequest
{
    public function __construct(
        public readonly ?string $uri = null,
        public readonly ?string $content = null,
        public readonly string $model = 'general',
        public readonly ?AudioFormat $audioFormat = null,
        public readonly ?TextNormalization $textNormalization = null,
        public readonly ?LanguageRestriction $languageRestriction = null,
        public readonly ?SpeakerLabeling $speakerLabeling = null,
        public readonly ?SpeechAnalysis $speechAnalysis = null,
        public readonly ?Summarization $summarization = null,
    ) {
        if ($uri === null && $content === null) {
            throw new InvalidArgumentException('Either uri or content must be provided');
        }

        if ($uri !== null && $content !== null) {
            throw new InvalidArgumentException('Only one of uri or content can be provided');
        }
    }

    public function toArray(): array
    {
        $data = [];

        if ($this->uri !== null) {
            $data['uri'] = $this->uri;
        }

        if ($this->content !== null) {
            $data['content'] = $this->content;
        }

        $recognitionModel = [
            'model' => $this->model,
        ];

        if ($this->audioFormat !== null) {
            $recognitionModel['audioFormat'] = $this->audioFormat->toArray();
        }

        if ($this->textNormalization !== null) {
            $recognitionModel['textNormalization'] = $this->textNormalization->toArray();
        }

        if ($this->languageRestriction !== null) {
            $recognitionModel['languageRestriction'] = $this->languageRestriction->toArray();
        }

        $data['recognitionModel'] = $recognitionModel;

        if ($this->speakerLabeling !== null) {
            $data['speakerLabeling'] = $this->speakerLabeling->toArray();
        }

        if ($this->speechAnalysis !== null) {
            $data['speechAnalysis'] = $this->speechAnalysis->toArray();
        }

        if ($this->summarization !== null) {
            $data['summarization'] = $this->summarization->toArray();
        }

        return $data;
    }
}
