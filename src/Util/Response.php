<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Util;

/**
 * Class Response
 *
 * @package Jalismrs\Stalactite\Client\Util
 */
class Response
{
    /** @var int $code */
    private int $code;
    /** @var array $headers */
    private array $headers;
    /** @var mixed $body */
    private $body;

    /**
     * Response constructor.
     *
     * @param int $code
     * @param array $headers
     * @param null $body
     *
     * @codeCoverageIgnore
     */
    public function __construct(int $code, array $headers = [], $body = null)
    {
        $this->code = $code;
        $this->headers = $headers;
        $this->body = $body;
    }

    /**
     * getCode
     *
     * @return int
     *
     * @codeCoverageIgnore
     */
    public function getCode(): int
    {
        return $this->code;
    }

    /**
     * getHeaders
     *
     * @return array
     *
     * @codeCoverageIgnore
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * getBody
     *
     * @return mixed|null
     *
     * @codeCoverageIgnore
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * isSuccessful
     *
     * @return bool
     */
    public function isSuccessful(): bool
    {
        return $this->code >= 200 && $this->code < 300;
    }
}
