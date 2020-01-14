<?php
declare(strict_types = 1);

namespace jalismrs\Stalactite\Client;

/**
 * Class Response
 * @package jalismrs\Stalactite\Client
 * Wrapper class for API responses
 */
class Response
{
    /** @var bool $success */
    private $success;

    /** @var null|string $error */
    private $error;

    /**
     * @var array $data
     * $data should contain all the response data except the success and error field
     */
    private $data;

    /**
     * @return bool
     */
    public function success(): bool
    {
        return $this->success;
    }

    /**
     * @param bool $success
     * @return Response
     */
    public function setSuccess(bool $success): Response
    {
        $this->success = $success;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getError(): ?string
    {
        return $this->error;
    }

    /**
     * @param string|null $error
     * @return Response
     */
    public function setError(?string $error): Response
    {
        $this->error = $error;
        return $this;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param array $data
     * @return Response
     */
    public function setData(array $data): Response
    {
        $this->data = $data;
        return $this;
    }
}
