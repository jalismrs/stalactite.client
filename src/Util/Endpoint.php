<?php

namespace Jalismrs\Stalactite\Client\Util;

use Closure;
use hunomina\DataValidator\Schema\Json\JsonSchema;

/**
 * Class Endpoint
 *
 * @package Jalismrs\Stalactite\Client\Util
 */
final class Endpoint
{
    /**
     * uri
     *
     * @var string
     */
    private string $uri;
    /**
     * method
     *
     * @var string
     */
    private string $method;
    /**
     * cacheable
     *
     * @var bool
     */
    private bool $cacheable;
    /**
     * responseValidationSchema
     *
     * @var \hunomina\DataValidator\Schema\Json\JsonSchema|null
     */
    private ?JsonSchema $responseValidationSchema = null;
    /**
     * responseFormatter
     *
     * @var \Closure|null
     */
    private ?Closure $responseFormatter = null;
    
    /**
     * Endpoint constructor.
     *
     * @param string $uri
     * @param string $method
     * @param bool   $cacheable
     *
     * @codeCoverageIgnore
     */
    public function __construct(string $uri, string $method = 'GET', bool $cacheable = true)
    {
        $this->uri = $uri;
        $this->method = $method;
        $this->cacheable = $cacheable;
    }
    
    /**
     * getUri
     *
     * @return string
     *
     * @codeCoverageIgnore
     */
    public function getUri(): string
    {
        return $this->uri;
    }
    
    /**
     * getMethod
     *
     * @return string
     *
     * @codeCoverageIgnore
     */
    public function getMethod(): string
    {
        return $this->method;
    }
    
    /**
     * isCacheable
     *
     * @return bool
     *
     * @codeCoverageIgnore
     */
    public function isCacheable(): bool
    {
        return $this->cacheable;
    }
    
    /**
     * getResponseValidationSchema
     *
     * @return \hunomina\DataValidator\Schema\Json\JsonSchema|null
     *
     * @codeCoverageIgnore
     */
    public function getResponseValidationSchema(): ?JsonSchema
    {
        return $this->responseValidationSchema;
    }
    
    /**
     * setResponseValidationSchema
     *
     * @param \hunomina\DataValidator\Schema\Json\JsonSchema|null $responseValidationSchema
     *
     * @return $this
     *
     * @codeCoverageIgnore
     */
    public function setResponseValidationSchema(?JsonSchema $responseValidationSchema): Endpoint
    {
        $this->responseValidationSchema = $responseValidationSchema;
        return $this;
    }
    
    /**
     * getResponseFormatter
     *
     * @return \Closure|null
     *
     * @codeCoverageIgnore
     */
    public function getResponseFormatter(): ?Closure
    {
        return $this->responseFormatter;
    }
    
    /**
     * setResponseFormatter
     *
     * @param \Closure|null $responseFormatter
     *
     * @return $this
     *
     * @codeCoverageIgnore
     */
    public function setResponseFormatter(?Closure $responseFormatter): Endpoint
    {
        $this->responseFormatter = $responseFormatter;
        return $this;
    }
}
