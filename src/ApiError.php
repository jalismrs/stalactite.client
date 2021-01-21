<?php

namespace Jalismrs\Stalactite\Client;

use hunomina\DataValidator\Rule\Json\JsonRule;

/**
 * Class ApiError
 *
 * @package Jalismrs\Stalactite\Client
 */
final class ApiError implements
    Schemable
{
    /**
     * type
     *
     * @var string $type
     */
    private string $type;
    /**
     * @var string $code
     */
    private string $code;
    /**
     * message
     *
     * @var string|null $message
     */
    private ?string $message;

    /**
     * ApiError constructor.
     *
     * @param string $type
     * @param string $code
     * @param string|null $message
     *
     * @codeCoverageIgnore
     */
    public function __construct(
        string $type,
        string $code,
        ?string $message = null
    )
    {
        $this->type = $type;
        $this->code = $code;
        $this->message = $message;
    }

    /**
     * getSchema
     *
     * @static
     * @return array[]
     *
     * @codeCoverageIgnore
     */
    public static function getSchema(): array
    {
        return [
            'type' => ['type' => JsonRule::STRING_TYPE],
            'code' => ['type' => JsonRule::STRING_TYPE],
            'message' => [
                'type' => JsonRule::STRING_TYPE,
                'null' => true,
            ],
        ];
    }

    /**
     * getType
     *
     * @return string
     *
     * @codeCoverageIgnore
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * getCode
     *
     * @return string
     *
     * @codeCoverageIgnore
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * getMessage
     *
     * @return string|null
     *
     * @codeCoverageIgnore
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }
}
