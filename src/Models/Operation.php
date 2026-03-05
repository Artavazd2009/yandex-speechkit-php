<?php

namespace Tigusigalpa\YandexSpeechKit\Models;

class Operation
{
    public function __construct(
        public readonly string $id,
        public readonly string $description = '',
        public readonly string $createdAt = '',
        public readonly string $createdBy = '',
        public readonly string $modifiedAt = '',
        public readonly bool $done = false,
        public readonly ?array $metadata = null,
        public readonly ?array $error = null,
        public readonly ?array $response = null
    ) {
    }

    public function isDone(): bool
    {
        return $this->done;
    }

    public function hasError(): bool
    {
        return $this->error !== null;
    }

    public function getErrorMessage(): ?string
    {
        if ($this->error === null) {
            return null;
        }

        return $this->error['message'] ?? 'Unknown error';
    }

    public function getErrorCode(): ?int
    {
        if ($this->error === null) {
            return null;
        }

        return $this->error['code'] ?? null;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? '',
            description: $data['description'] ?? '',
            createdAt: $data['createdAt'] ?? '',
            createdBy: $data['createdBy'] ?? '',
            modifiedAt: $data['modifiedAt'] ?? '',
            done: $data['done'] ?? false,
            metadata: $data['metadata'] ?? null,
            error: $data['error'] ?? null,
            response: $data['response'] ?? null
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'description' => $this->description,
            'createdAt' => $this->createdAt,
            'createdBy' => $this->createdBy,
            'modifiedAt' => $this->modifiedAt,
            'done' => $this->done,
            'metadata' => $this->metadata,
            'error' => $this->error,
            'response' => $this->response,
        ];
    }
}
