<?php

namespace Jalismrs\Stalactite\Client;

use hunomina\DataValidator\Rule\Json\JsonRule;

final class ApiError implements Schemable
{
    private string $type;
    private int $code;
    private ?string $message;

    public function __construct(string $type, int $code, ?string $message = null)
    {
        $this->type = $type;
        $this->code = $code;
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return int
     */
    public function getCode(): int
    {
        return $this->code;
    }

    /**
     * @return string|null
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }

    public static function getSchema(): array
    {
        return [
            'type' => ['type' => JsonRule::STRING_TYPE],
            'code' => ['type' => JsonRule::INTEGER_TYPE],
            'message' => ['type' => JsonRule::STRING_TYPE, 'null' => true],
        ];
    }
}