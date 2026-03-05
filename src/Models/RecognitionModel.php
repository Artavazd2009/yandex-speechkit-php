<?php

namespace Tigusigalpa\YandexSpeechKit\Models;

class RecognitionModel
{
    public function __construct(
        public readonly string $model = 'general',
        public readonly ?AudioFormat $audioFormat = null,
        public readonly ?TextNormalization $textNormalization = null,
        public readonly ?LanguageRestriction $languageRestriction = null
    ) {
    }

    public function toArray(): array
    {
        $data = [
            'model' => $this->model,
        ];

        if ($this->audioFormat !== null) {
            $data['audioFormat'] = $this->audioFormat->toArray();
        }

        if ($this->textNormalization !== null) {
            $data['textNormalization'] = $this->textNormalization->toArray();
        }

        if ($this->languageRestriction !== null) {
            $data['languageRestriction'] = $this->languageRestriction->toArray();
        }

        return $data;
    }
}
