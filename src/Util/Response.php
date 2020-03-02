<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Util;

/**
 * Response
 *
 * Wrapper class for API responses
 *
 * @package Jalismrs\Stalactite\Service
 */
class Response
{
    /** @var int $code */
    private int $code;

    /** @var array $headers */
    private array $headers;

    /** @var mixed $body */
    private $body;

    public function __construct(int $code, array $headers = [], $body = null)
    {
        $this->code = $code;
        $this->headers = $headers;
        $this->body = $body;
    }

    /**
     * @return int
     */
    public function getCode(): int
    {
        return $this->code;
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @return mixed
     */
    public function getBody()
    {
        return $this->body;
    }
}
