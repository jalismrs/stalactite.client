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
    /**
     * should contain all the response data except the success and error field
     */
    private ?array $data;

    private ?string $error;

    private bool $success;

    /**
     * Response constructor.
     *
     * @param bool $success
     * @param string|null $error
     * @param array|null $data
     */
    public function __construct(
        bool $success,
        ?string $error,
        ?array $data
    )
    {
        $this->success = $success;
        $this->error = $error;
        $this->data = $data;
    }

    /**
     * isSuccess
     *
     * @return bool
     */
    public function isSuccess(): bool
    {
        return $this->success;
    }

    /**
     * getError
     *
     * @return null|string
     */
    public function getError(): ?string
    {
        return $this->error;
    }

    /**
     * @return array|null
     */
    public function getData(): ?array
    {
        return $this->data;
    }
}
