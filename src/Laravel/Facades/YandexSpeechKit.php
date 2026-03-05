<?php

namespace Tigusigalpa\YandexSpeechKit\Laravel\Facades;

use Illuminate\Support\Facades\Facade;
use Tigusigalpa\YandexSpeechKit\YandexSpeechKitClient;

/**
 * @method static \Tigusigalpa\YandexSpeechKit\Models\Operation recognizeFileAsync(\Tigusigalpa\YandexSpeechKit\Models\RecognitionRequest $request)
 * @method static \Tigusigalpa\YandexSpeechKit\Models\RecognitionResult getRecognition(string $operationId)
 * @method static bool deleteRecognition(string $operationId)
 * @method static \Tigusigalpa\YandexSpeechKit\Models\Operation getOperation(string $operationId)
 * @method static \Tigusigalpa\YandexSpeechKit\Models\Operation cancelOperation(string $operationId)
 * @method static \Tigusigalpa\YandexSpeechKit\Models\RecognitionResult recognizeAndWait(\Tigusigalpa\YandexSpeechKit\Models\RecognitionRequest $request, int $pollIntervalSeconds = 10, int $maxWaitSeconds = 14400)
 * @method static \Tigusigalpa\YandexCloudClient\YandexCloudClient getCloudClient()
 *
 * @see \Tigusigalpa\YandexSpeechKit\YandexSpeechKitClient
 */
class YandexSpeechKit extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return YandexSpeechKitClient::class;
    }
}
