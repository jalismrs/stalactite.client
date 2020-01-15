<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client;

/**
 * Response
 *
 * Wrapper class for API responses
 *
 * @package Jalismrs\Stalactite\Client
 */
class Response
{
    /**
     * should contain all the response data except the success and error field
     *
     * @var array
     */
    private $data;
    /**
     * @var null|string
     */
    private $error;
    /**
     * @var bool
     */
    private $success;
    
    /**
     * Response constructor.
     *
     * @param bool        $success
     * @param null|string $error
     * @param array|null  $data
     */
    public function __construct(
        bool $success,
        ?string $error,
        array $data = null
    ) {
        $this->success = $success;
        $this->error   = $error;
        $this->data    = $data;
    }
    
    /**
     * success
     *
     * @return bool
     */
    public function success() : bool
    {
        return $this->success;
    }
    
    /**
     * getError
     *
     * @return null|string
     */
    public function getError() : ?string
    {
        return $this->error;
    }
    
    /**
     * getData
     *
     * @return array
     */
    public function getData() : array
    {
        return $this->data;
    }
}
