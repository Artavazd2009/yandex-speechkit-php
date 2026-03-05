<?php

namespace Tigusigalpa\YandexSpeechKit;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Tigusigalpa\YandexCloudClient\YandexCloudClient;
use Tigusigalpa\YandexSpeechKit\Exceptions\AuthenticationException;
use Tigusigalpa\YandexSpeechKit\Exceptions\OperationException;
use Tigusigalpa\YandexSpeechKit\Exceptions\RecognitionException;
use Tigusigalpa\YandexSpeechKit\Models\Operation;
use Tigusigalpa\YandexSpeechKit\Models\RecognitionRequest;
use Tigusigalpa\YandexSpeechKit\Models\RecognitionResult;

class YandexSpeechKitClient
{
    private const STT_BASE_URL = 'https://stt.api.cloud.yandex.net';
    private const OPERATION_BASE_URL = 'https://operation.api.cloud.yandex.net';

    private Client $httpClient;

    public function __construct(
        private readonly YandexCloudClient $cloudClient,
        private readonly string $folderId,
        private readonly ?string $apiKey = null
    ) {
        $this->httpClient = new Client([
            'timeout' => 30,
            'connect_timeout' => 10,
        ]);
    }

    /**
     * Start asynchronous recognition of an audio file.
     * Corresponds to: POST /stt/v3/recognizeFileAsync
     *
     * @param RecognitionRequest $request
     * @return Operation
     * @throws RecognitionException
     * @throws AuthenticationException
     */
    public function recognizeFileAsync(RecognitionRequest $request): Operation
    {
        $url = self::STT_BASE_URL . '/stt/v3/recognizeFileAsync';

        try {
            $response = $this->httpClient->post($url, [
                'headers' => $this->getHeaders(),
                'json' => $request->toArray(),
            ]);

            $data = json_decode($response->getBody()->getContents(), true);

            if (!is_array($data)) {
                throw new RecognitionException('Invalid response format from API');
            }

            return Operation::fromArray($data);
        } catch (GuzzleException $e) {
            $this->handleGuzzleException($e, RecognitionException::class);
        }
    }

    /**
     * Get recognition results for a completed operation.
     * Corresponds to: GET /stt/v3/getRecognition
     *
     * @param string $operationId
     * @return RecognitionResult
     * @throws RecognitionException
     * @throws AuthenticationException
     */
    public function getRecognition(string $operationId): RecognitionResult
    {
        $url = self::STT_BASE_URL . '/stt/v3/getRecognition';

        try {
            $response = $this->httpClient->get($url, [
                'headers' => $this->getHeaders(),
                'query' => ['operation_id' => $operationId],
            ]);

            $body = $response->getBody()->getContents();
            $responses = $this->parseNdjson($body);

            return RecognitionResult::fromResponses($responses);
        } catch (GuzzleException $e) {
            $this->handleGuzzleException($e, RecognitionException::class);
        }
    }

    /**
     * Delete recognition results by operation ID.
     * Corresponds to: DELETE /stt/v3/deleteRecognition
     *
     * @param string $operationId
     * @return bool
     * @throws RecognitionException
     * @throws AuthenticationException
     */
    public function deleteRecognition(string $operationId): bool
    {
        $url = self::STT_BASE_URL . '/stt/v3/deleteRecognition';

        try {
            $response = $this->httpClient->delete($url, [
                'headers' => $this->getHeaders(),
                'query' => ['operation_id' => $operationId],
            ]);

            return $response->getStatusCode() === 200;
        } catch (GuzzleException $e) {
            $this->handleGuzzleException($e, RecognitionException::class);
        }
    }

    /**
     * Get the current status of an operation.
     * Corresponds to: GET /operations/{operationId}
     *
     * @param string $operationId
     * @return Operation
     * @throws OperationException
     * @throws AuthenticationException
     */
    public function getOperation(string $operationId): Operation
    {
        $url = self::OPERATION_BASE_URL . '/operations/' . $operationId;

        try {
            $response = $this->httpClient->get($url, [
                'headers' => $this->getHeaders(),
            ]);

            $data = json_decode($response->getBody()->getContents(), true);

            if (!is_array($data)) {
                throw new OperationException('Invalid response format from API');
            }

            return Operation::fromArray($data);
        } catch (GuzzleException $e) {
            $this->handleGuzzleException($e, OperationException::class);
        }
    }

    /**
     * Cancel an operation.
     * Corresponds to: GET /operations/{operationId}:cancel
     *
     * @param string $operationId
     * @return Operation
     * @throws OperationException
     * @throws AuthenticationException
     */
    public function cancelOperation(string $operationId): Operation
    {
        $url = self::OPERATION_BASE_URL . '/operations/' . $operationId . ':cancel';

        try {
            $response = $this->httpClient->get($url, [
                'headers' => $this->getHeaders(),
            ]);

            $data = json_decode($response->getBody()->getContents(), true);

            if (!is_array($data)) {
                throw new OperationException('Invalid response format from API');
            }

            return Operation::fromArray($data);
        } catch (GuzzleException $e) {
            $this->handleGuzzleException($e, OperationException::class);
        }
    }

    /**
     * High-level helper: recognize a file and wait for completion.
     * Polls the operation status until done, then returns results.
     *
     * @param RecognitionRequest $request
     * @param int $pollIntervalSeconds How often to poll for status (default: 10)
     * @param int $maxWaitSeconds Maximum time to wait (default: 14400 = 4 hours)
     * @return RecognitionResult
     * @throws RecognitionException
     * @throws OperationException
     * @throws AuthenticationException
     */
    public function recognizeAndWait(
        RecognitionRequest $request,
        int $pollIntervalSeconds = 10,
        int $maxWaitSeconds = 14400
    ): RecognitionResult {
        $operation = $this->recognizeFileAsync($request);
        $startTime = time();

        while (!$operation->isDone()) {
            if (time() - $startTime > $maxWaitSeconds) {
                throw new OperationException(
                    "Operation timeout: exceeded {$maxWaitSeconds} seconds"
                );
            }

            sleep($pollIntervalSeconds);
            $operation = $this->getOperation($operation->id);
        }

        if ($operation->hasError()) {
            throw new RecognitionException(
                $operation->getErrorMessage() ?? 'Recognition failed',
                $operation->getErrorCode()
            );
        }

        return $this->getRecognition($operation->id);
    }

    /**
     * Get the underlying YandexCloudClient instance.
     *
     * @return YandexCloudClient
     */
    public function getCloudClient(): YandexCloudClient
    {
        return $this->cloudClient;
    }

    /**
     * Get HTTP headers for API requests.
     *
     * @return array
     * @throws AuthenticationException
     */
    private function getHeaders(): array
    {
        $headers = [
            'Content-Type' => 'application/json',
            'x-folder-id' => $this->folderId,
        ];

        if ($this->apiKey !== null) {
            $headers['Authorization'] = 'Api-Key ' . $this->apiKey;
        } else {
            try {
                $iamToken = $this->cloudClient->getAuthManager()->getIamToken();
                $headers['Authorization'] = 'Bearer ' . $iamToken;
            } catch (\Throwable $e) {
                throw new AuthenticationException(
                    'Failed to obtain IAM token: ' . $e->getMessage(),
                    0,
                    $e
                );
            }
        }

        return $headers;
    }

    /**
     * Parse newline-delimited JSON (NDJSON) response.
     *
     * @param string $body
     * @return array
     */
    private function parseNdjson(string $body): array
    {
        $lines = explode("\n", trim($body));
        $responses = [];

        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '') {
                continue;
            }

            $decoded = json_decode($line, true);
            if (is_array($decoded)) {
                $responses[] = $decoded;
            }
        }

        return $responses;
    }

    /**
     * Handle Guzzle exceptions and convert to library exceptions.
     *
     * @param GuzzleException $e
     * @param string $exceptionClass
     * @return never
     * @throws AuthenticationException
     * @throws RecognitionException
     * @throws OperationException
     */
    private function handleGuzzleException(GuzzleException $e, string $exceptionClass): never
    {
        $message = $e->getMessage();
        $code = 0;
        $apiErrorCode = null;
        $apiErrorMessage = null;

        if ($e->hasResponse()) {
            $response = $e->getResponse();
            $statusCode = $response->getStatusCode();
            $body = $response->getBody()->getContents();

            if ($statusCode === 401 || $statusCode === 403) {
                throw new AuthenticationException(
                    "Authentication failed: {$message}",
                    $statusCode,
                    $e
                );
            }

            $errorData = json_decode($body, true);
            if (is_array($errorData)) {
                $apiErrorCode = $errorData['code'] ?? $statusCode;
                $apiErrorMessage = $errorData['message'] ?? $message;
            }

            $code = $statusCode;
        }

        throw new $exceptionClass(
            $apiErrorMessage ?? $message,
            $apiErrorCode,
            $apiErrorMessage,
            $code,
            $e
        );
    }
}
