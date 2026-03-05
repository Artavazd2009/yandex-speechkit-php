<?php

namespace Tigusigalpa\YandexSpeechKit\Tests\Unit\Models;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Tigusigalpa\YandexSpeechKit\Models\AudioFormat;
use Tigusigalpa\YandexSpeechKit\Models\LanguageRestriction;
use Tigusigalpa\YandexSpeechKit\Models\RecognitionRequest;
use Tigusigalpa\YandexSpeechKit\Models\SpeakerLabeling;
use Tigusigalpa\YandexSpeechKit\Models\TextNormalization;

class RecognitionRequestTest extends TestCase
{
    public function testCreateWithUri()
    {
        $request = new RecognitionRequest(
            uri: 'https://storage.yandexcloud.net/bucket/audio.wav',
            audioFormat: AudioFormat::container('WAV')
        );

        $this->assertEquals('https://storage.yandexcloud.net/bucket/audio.wav', $request->uri);
        $this->assertNull($request->content);
    }

    public function testCreateWithContent()
    {
        $request = new RecognitionRequest(
            content: base64_encode('audio data'),
            audioFormat: AudioFormat::container('WAV')
        );

        $this->assertNull($request->uri);
        $this->assertNotNull($request->content);
    }

    public function testThrowsExceptionWhenBothUriAndContentProvided()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Only one of uri or content can be provided');

        new RecognitionRequest(
            uri: 'https://storage.yandexcloud.net/bucket/audio.wav',
            content: base64_encode('audio data'),
            audioFormat: AudioFormat::container('WAV')
        );
    }

    public function testThrowsExceptionWhenNeitherUriNorContentProvided()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Either uri or content must be provided');

        new RecognitionRequest(
            audioFormat: AudioFormat::container('WAV')
        );
    }

    public function testToArrayWithAllOptions()
    {
        $request = new RecognitionRequest(
            uri: 'https://storage.yandexcloud.net/bucket/audio.wav',
            model: 'general',
            audioFormat: AudioFormat::container('WAV'),
            textNormalization: new TextNormalization(),
            languageRestriction: new LanguageRestriction(),
            speakerLabeling: new SpeakerLabeling()
        );

        $array = $request->toArray();

        $this->assertArrayHasKey('uri', $array);
        $this->assertArrayHasKey('recognitionModel', $array);
        $this->assertArrayHasKey('speakerLabeling', $array);
        $this->assertEquals('general', $array['recognitionModel']['model']);
    }

    public function testToArrayWithMinimalOptions()
    {
        $request = new RecognitionRequest(
            uri: 'https://storage.yandexcloud.net/bucket/audio.wav'
        );

        $array = $request->toArray();

        $this->assertArrayHasKey('uri', $array);
        $this->assertArrayHasKey('recognitionModel', $array);
        $this->assertEquals('general', $array['recognitionModel']['model']);
    }
}
