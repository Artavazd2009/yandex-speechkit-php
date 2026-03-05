<?php

namespace Tigusigalpa\YandexSpeechKit\Exceptions;

class OperationException extends SpeechKitException
{
    public function __construct(
        string $message = "",
        private readonly ?int $apiErrorCode = null,
        private readonly ?string $apiErrorMessage = null,
        int $code = 0,
        ?\Throwable $previous = null
    ) {
        if ($apiErrorMessage && !$message) {
            $message = $apiErrorMessage;
        }
        
        if ($apiErrorCode && $message) {
            $message = "Operation Error [{$apiErrorCode}]: {$message}";
        }
        
        parent::__construct($message, $code, $previous);
    }

    public function getApiErrorCode(): ?int
    {
        return $this->apiErrorCode;
    }

    public function getApiErrorMessage(): ?string
    {
        return $this->apiErrorMessage;
    }
}
