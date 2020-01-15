<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client;

/**
 * Client
 *
 * @package Jalismrs\Stalactite\Client
 */
class Client extends
    ClientAbstract
{
    
    /**
     * access
     *
     * @return \Jalismrs\Stalactite\Client\AccessManagement\Client
     */
    public function accessManagement() : AccessManagement\Client
    {
        static $client = null;
        
        if (null === $client) {
            $client = new AccessManagement\Client(
                $this->host,
                $this->userAgent,
                $this->httpClient
            );
        }
        
        return $client;
    }
    
    /**
     * authentification
     *
     * @return \Jalismrs\Stalactite\Client\Authentication\Client
     */
    public function authentification() : Authentication\Client
    {
        static $client = null;
    
        if (null === $client) {
            $client = new Authentication\Client(
                $this->host,
                $this->userAgent,
                $this->httpClient
            );
        }
        
        return $client;
    }
    
    /**
     * data
     *
     * @return \Jalismrs\Stalactite\Client\DataManagement\Client
     */
    public function dataManagement() : DataManagement\Client
    {
        static $client = null;
    
        if (null === $client) {
            $client = new DataManagement\Client(
                $this->host,
                $this->userAgent,
                $this->httpClient
            );
        }
        
        return $client;
    }
}
