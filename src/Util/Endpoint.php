<?php

namespace Jalismrs\Stalactite\Client\Util;

use Closure;
use hunomina\DataValidator\Schema\Json\JsonSchema;

/**
 * Class Endpoint
 * @package Jalismrs\Stalactite\Client\Util
 */
class Endpoint
{
    private string $uri;

    private string $method;

    private bool $cacheable;

    private ?JsonSchema $responseValidationSchema = null;

    private ?Closure $responseFormatter = null;

    public function __construct(string $uri, string $method = 'GET', bool $cacheable = true)
    {
        $this->uri = $uri;
        $this->method = $method;
        $this->cacheable = $cacheable;
    }

    /**
     * @return string
     */
    public function getUri(): string
    {
        return $this->uri;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @return bool
     */
    public function isCacheable(): bool
    {
        return $this->cacheable;
    }

    /**
     * @return JsonSchema|null
     */
    public function getResponseValidationSchema(): ?JsonSchema
    {
        return $this->responseValidationSchema;
    }

    /**
     * @param JsonSchema|null $responseValidationSchema
     * @return Endpoint
     */
    public function setResponseValidationSchema(?JsonSchema $responseValidationSchema): Endpoint
    {
        $this->responseValidationSchema = $responseValidationSchema;
        return $this;
    }

    /**
     * @return Closure|null
     */
    public function getResponseFormatter(): ?Closure
    {
        return $this->responseFormatter;
    }

    /**
     * @param Closure|null $responseFormatter
     * @return Endpoint
     */
    public function setResponseFormatter(?Closure $responseFormatter): Endpoint
    {
        $this->responseFormatter = $responseFormatter;
        return $this;
    }
}