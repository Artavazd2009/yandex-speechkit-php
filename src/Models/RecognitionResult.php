<?php

namespace Tigusigalpa\YandexSpeechKit\Models;

class RecognitionResult
{
    public function __construct(
        public readonly string $fullText,
        public readonly array $alternatives,
        public readonly array $words,
        public readonly ?array $speakerAnalysis = null,
        public readonly ?array $conversationAnalysis = null,
        public readonly ?array $summarization = null
    ) {
    }

    public static function fromResponses(array $responses): self
    {
        $fullText = '';
        $alternatives = [];
        $words = [];
        $speakerAnalysis = null;
        $conversationAnalysis = null;
        $summarization = null;

        foreach ($responses as $response) {
            if (isset($response['final'])) {
                $final = $response['final'];
                
                if (isset($final['alternatives']) && is_array($final['alternatives'])) {
                    foreach ($final['alternatives'] as $alternative) {
                        $alternatives[] = $alternative;
                        
                        if (isset($alternative['text'])) {
                            $fullText .= ($fullText ? ' ' : '') . $alternative['text'];
                        }
                        
                        if (isset($alternative['words']) && is_array($alternative['words'])) {
                            $words = array_merge($words, $alternative['words']);
                        }
                    }
                }
            }

            if (isset($response['speakerAnalysis'])) {
                $speakerAnalysis = $response['speakerAnalysis'];
            }

            if (isset($response['conversationAnalysis'])) {
                $conversationAnalysis = $response['conversationAnalysis'];
            }

            if (isset($response['summarization'])) {
                $summarization = $response['summarization'];
            }
        }

        return new self(
            fullText: $fullText,
            alternatives: $alternatives,
            words: $words,
            speakerAnalysis: $speakerAnalysis,
            conversationAnalysis: $conversationAnalysis,
            summarization: $summarization
        );
    }

    public function toArray(): array
    {
        return [
            'fullText' => $this->fullText,
            'alternatives' => $this->alternatives,
            'words' => $this->words,
            'speakerAnalysis' => $this->speakerAnalysis,
            'conversationAnalysis' => $this->conversationAnalysis,
            'summarization' => $this->summarization,
        ];
    }
}
