<?php
declare(strict_types = 1);

namespace jalismrs\Stalactite\Client;

/**
 * Response
 *
 * Wrapper class for API responses
 *
 * @package jalismrs\Stalactite\Client
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
     */
    public function __construct()
    {
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
     * setSuccess
     *
     * @param bool $success
     *
     * @return \jalismrs\Stalactite\Client\Response
     */
    public function setSuccess(bool $success) : Response
    {
        $this->success = $success;
        
        return $this;
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
     * setError
     *
     * @param null|string $error
     *
     * @return \jalismrs\Stalactite\Client\Response
     */
    public function setError(?string $error) : Response
    {
        $this->error = $error;
        
        return $this;
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
    
    /**
     * setData
     *
     * @param array $data
     *
     * @return \jalismrs\Stalactite\Client\Response
     */
    public function setData(array $data) : Response
    {
        $this->data = $data;
        
        return $this;
    }
}
