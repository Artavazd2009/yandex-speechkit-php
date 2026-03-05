<?php

namespace Tigusigalpa\YandexSpeechKit\Tests\Unit;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Mockery;
use PHPUnit\Framework\TestCase;
use Tigusigalpa\YandexCloudClient\YandexCloudClient;
use Tigusigalpa\YandexSpeechKit\Exceptions\AuthenticationException;
use Tigusigalpa\YandexSpeechKit\Exceptions\RecognitionException;
use Tigusigalpa\YandexSpeechKit\Models\AudioFormat;
use Tigusigalpa\YandexSpeechKit\Models\Operation;
use Tigusigalpa\YandexSpeechKit\Models\RecognitionRequest;
use Tigusigalpa\YandexSpeechKit\YandexSpeechKitClient;

class YandexSpeechKitClientTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testRecognizeFileAsyncSuccess()
    {
        $mockCloudClient = $this->createMockCloudClient('test-iam-token');
        
        $client = new YandexSpeechKitClient($mockCloudClient, 'test-folder-id');
        
        $reflection = new \ReflectionClass($client);
        $httpClientProperty = $reflection->getProperty('httpClient');
        $httpClientProperty->setAccessible(true);
        
        $mock = new MockHandler([
            new Response(200, [], json_encode([
                'id' => 'op123',
                'done' => false,
                'createdAt' => '2025-03-05T10:00:00Z',
            ])),
        ]);
        
        $handlerStack = HandlerStack::create($mock);
        $httpClient = new Client(['handler' => $handlerStack]);
        $httpClientProperty->setValue($client, $httpClient);

        $request = new RecognitionRequest(
            uri: 'https://storage.yandexcloud.net/bucket/audio.wav',
            audioFormat: AudioFormat::container('WAV')
        );

        $operation = $client->recognizeFileAsync($request);

        $this->assertInstanceOf(Operation::class, $operation);
        $this->assertEquals('op123', $operation->id);
        $this->assertFalse($operation->isDone());
    }

    public function testGetRecognitionSuccess()
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
                        'text' => 'Hello world',
                        'words' => [
                            ['text' => 'Hello', 'startTimeMs' => '0', 'endTimeMs' => '500'],
                            ['text' => 'world', 'startTimeMs' => '500', 'endTimeMs' => '1000'],
                        ],
                    ],
                ],
            ],
        ]);
        
        $mock = new MockHandler([
            new Response(200, [], $ndjsonResponse),
        ]);
        
        $handlerStack = HandlerStack::create($mock);
        $httpClient = new Client(['handler' => $handlerStack]);
        $httpClientProperty->setValue($client, $httpClient);

        $result = $client->getRecognition('op123');

        $this->assertEquals('Hello world', $result->fullText);
        $this->assertCount(2, $result->words);
    }

    public function testDeleteRecognitionSuccess()
    {
        $mockCloudClient = $this->createMockCloudClient('test-iam-token');
        
        $client = new YandexSpeechKitClient($mockCloudClient, 'test-folder-id');
        
        $reflection = new \ReflectionClass($client);
        $httpClientProperty = $reflection->getProperty('httpClient');
        $httpClientProperty->setAccessible(true);
        
        $mock = new MockHandler([
            new Response(200, [], ''),
        ]);
        
        $handlerStack = HandlerStack::create($mock);
        $httpClient = new Client(['handler' => $handlerStack]);
        $httpClientProperty->setValue($client, $httpClient);

        $result = $client->deleteRecognition('op123');

        $this->assertTrue($result);
    }

    public function testGetOperationSuccess()
    {
        $mockCloudClient = $this->createMockCloudClient('test-iam-token');
        
        $client = new YandexSpeechKitClient($mockCloudClient, 'test-folder-id');
        
        $reflection = new \ReflectionClass($client);
        $httpClientProperty = $reflection->getProperty('httpClient');
        $httpClientProperty->setAccessible(true);
        
        $mock = new MockHandler([
            new Response(200, [], json_encode([
                'id' => 'op123',
                'done' => true,
                'createdAt' => '2025-03-05T10:00:00Z',
            ])),
        ]);
        
        $handlerStack = HandlerStack::create($mock);
        $httpClient = new Client(['handler' => $handlerStack]);
        $httpClientProperty->setValue($client, $httpClient);

        $operation = $client->getOperation('op123');

        $this->assertInstanceOf(Operation::class, $operation);
        $this->assertTrue($operation->isDone());
    }

    public function testAuthenticationExceptionOnInvalidToken()
    {
        $this->expectException(AuthenticationException::class);

        $mockCloudClient = $this->createMockCloudClient('test-iam-token');
        
        $client = new YandexSpeechKitClient($mockCloudClient, 'test-folder-id');
        
        $reflection = new \ReflectionClass($client);
        $httpClientProperty = $reflection->getProperty('httpClient');
        $httpClientProperty->setAccessible(true);
        
        $mock = new MockHandler([
            new RequestException(
                'Unauthorized',
                new Request('POST', 'test'),
                new Response(401, [], 'Unauthorized')
            ),
        ]);
        
        $handlerStack = HandlerStack::create($mock);
        $httpClient = new Client(['handler' => $handlerStack]);
        $httpClientProperty->setValue($client, $httpClient);

        $request = new RecognitionRequest(
            uri: 'https://storage.yandexcloud.net/bucket/audio.wav',
            audioFormat: AudioFormat::container('WAV')
        );

        $client->recognizeFileAsync($request);
    }

    public function testApiKeyAuthentication()
    {
        $mockCloudClient = $this->createMockCloudClient('test-iam-token');
        
        $client = new YandexSpeechKitClient($mockCloudClient, 'test-folder-id', 'test-api-key');
        
        $reflection = new \ReflectionClass($client);
        $httpClientProperty = $reflection->getProperty('httpClient');
        $httpClientProperty->setAccessible(true);
        
        $mock = new MockHandler([
            new Response(200, [], json_encode([
                'id' => 'op123',
                'done' => false,
            ])),
        ]);
        
        $handlerStack = HandlerStack::create($mock);
        $httpClient = new Client(['handler' => $handlerStack]);
        $httpClientProperty->setValue($client, $httpClient);

        $request = new RecognitionRequest(
            uri: 'https://storage.yandexcloud.net/bucket/audio.wav',
            audioFormat: AudioFormat::container('WAV')
        );

        $operation = $client->recognizeFileAsync($request);

        $this->assertInstanceOf(Operation::class, $operation);
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
