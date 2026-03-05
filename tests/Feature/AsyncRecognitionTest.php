<?php

namespace Tigusigalpa\YandexSpeechKit\Tests\Feature;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Mockery;
use PHPUnit\Framework\TestCase;
use Tigusigalpa\YandexCloudClient\YandexCloudClient;
use Tigusigalpa\YandexSpeechKit\Exceptions\OperationException;
use Tigusigalpa\YandexSpeechKit\Exceptions\RecognitionException;
use Tigusigalpa\YandexSpeechKit\Models\AudioFormat;
use Tigusigalpa\YandexSpeechKit\Models\RecognitionRequest;
use Tigusigalpa\YandexSpeechKit\YandexSpeechKitClient;

class AsyncRecognitionTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testFullRecognitionWorkflow()
    {
        $mockCloudClient = $this->createMockCloudClient('test-iam-token');
        
        $client = new YandexSpeechKitClient($mockCloudClient, 'test-folder-id');
        
        $reflection = new \ReflectionClass($client);
        $httpClientProperty = $reflection->getProperty('httpClient');
        $httpClientProperty->setAccessible(true);
        
        $ndjsonResponse = json_encode([
            'final' => [
                'alternatives' => [
                    [
                        'text' => 'Test transcription',
                        'words' => [
                            ['text' => 'Test', 'startTimeMs' => '0', 'endTimeMs' => '500'],
                            ['text' => 'transcription', 'startTimeMs' => '500', 'endTimeMs' => '1500'],
                        ],
                    ],
                ],
            ],
        ]);
        
        $mock = new MockHandler([
            new Response(200, [], json_encode(['id' => 'op123', 'done' => false])),
            new Response(200, [], json_encode(['id' => 'op123', 'done' => false])),
            new Response(200, [], json_encode(['id' => 'op123', 'done' => true])),
            new Response(200, [], $ndjsonResponse),
            new Response(200, [], ''),
        ]);
        
        $handlerStack = HandlerStack::create($mock);
        $httpClient = new Client(['handler' => $handlerStack]);
        $httpClientProperty->setValue($client, $httpClient);

        $request = new RecognitionRequest(
            uri: 'https://storage.yandexcloud.net/bucket/audio.wav',
            audioFormat: AudioFormat::container('WAV')
        );

        $operation = $client->recognizeFileAsync($request);
        $this->assertEquals('op123', $operation->id);

        do {
            $operation = $client->getOperation($operation->id);
        } while (!$operation->isDone());

        $this->assertTrue($operation->isDone());

        $result = $client->getRecognition($operation->id);
        $this->assertEquals('Test transcription', $result->fullText);

        $deleted = $client->deleteRecognition($operation->id);
        $this->assertTrue($deleted);
    }

    public function testRecognizeAndWaitSuccess()
    {
        $mockCloudClient = $this->createMockCloudClient('test-iam-token');
        
        $client = new YandexSpeechKitClient($mockCloudClient, 'test-folder-id');
        
        $reflection = new \ReflectionClass($client);
        $httpClientProperty = $reflection->getProperty('httpClient');
        $httpClientProperty->setAccessible(true);
        
        $ndjsonResponse = json_encode([
            'final' => [
                'alternatives' => [
                    ['text' => 'Quick result'],
                ],
            ],
        ]);
        
        $mock = new MockHandler([
            new Response(200, [], json_encode(['id' => 'op123', 'done' => false])),
            new Response(200, [], json_encode(['id' => 'op123', 'done' => true])),
            new Response(200, [], $ndjsonResponse),
        ]);
        
        $handlerStack = HandlerStack::create($mock);
        $httpClient = new Client(['handler' => $handlerStack]);
        $httpClientProperty->setValue($client, $httpClient);

        $request = new RecognitionRequest(
            uri: 'https://storage.yandexcloud.net/bucket/audio.wav',
            audioFormat: AudioFormat::container('WAV')
        );

        $result = $client->recognizeAndWait($request, 1, 10);

        $this->assertEquals('Quick result', $result->fullText);
    }

    public function testRecognizeAndWaitTimeout()
    {
        $this->expectException(OperationException::class);
        $this->expectExceptionMessage('Operation timeout');

        $mockCloudClient = $this->createMockCloudClient('test-iam-token');
        
        $client = new YandexSpeechKitClient($mockCloudClient, 'test-folder-id');
        
        $reflection = new \ReflectionClass($client);
        $httpClientProperty = $reflection->getProperty('httpClient');
        $httpClientProperty->setAccessible(true);
        
        $mock = new MockHandler([
            new Response(200, [], json_encode(['id' => 'op123', 'done' => false])),
            new Response(200, [], json_encode(['id' => 'op123', 'done' => false])),
            new Response(200, [], json_encode(['id' => 'op123', 'done' => false])),
        ]);
        
        $handlerStack = HandlerStack::create($mock);
        $httpClient = new Client(['handler' => $handlerStack]);
        $httpClientProperty->setValue($client, $httpClient);

        $request = new RecognitionRequest(
            uri: 'https://storage.yandexcloud.net/bucket/audio.wav',
            audioFormat: AudioFormat::container('WAV')
        );

        $client->recognizeAndWait($request, 1, 2);
    }

    public function testRecognizeAndWaitWithError()
    {
        $this->expectException(RecognitionException::class);

        $mockCloudClient = $this->createMockCloudClient('test-iam-token');
        
        $client = new YandexSpeechKitClient($mockCloudClient, 'test-folder-id');
        
        $reflection = new \ReflectionClass($client);
        $httpClientProperty = $reflection->getProperty('httpClient');
        $httpClientProperty->setAccessible(true);
        
        $mock = new MockHandler([
            new Response(200, [], json_encode(['id' => 'op123', 'done' => false])),
            new Response(200, [], json_encode([
                'id' => 'op123',
                'done' => true,
                'error' => ['code' => 500, 'message' => 'Processing failed'],
            ])),
        ]);
        
        $handlerStack = HandlerStack::create($mock);
        $httpClient = new Client(['handler' => $handlerStack]);
        $httpClientProperty->setValue($client, $httpClient);

        $request = new RecognitionRequest(
            uri: 'https://storage.yandexcloud.net/bucket/audio.wav',
            audioFormat: AudioFormat::container('WAV')
        );

        $client->recognizeAndWait($request, 1, 10);
    }

    private function createMockCloudClient(string $iamToken): YandexCloudClient
    {
        $mockAuthManager = Mockery::mock();
        $mockAuthManager->shouldReceive('getIamToken')
            ->andReturn($iamToken);

        $mockCloudClient = Mockery::mock(YandexCloudClient::class);
        $mockCloudClient->shouldReceive('getAuthManager')
            ->andReturn($mockAuthManager);

        return $mockCloudClient;
    }
}
