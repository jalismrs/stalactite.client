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
    /*
     * -------------------------------------------------------------------------
     * Clients -----------------------------------------------------------------
     * -------------------------------------------------------------------------
     */
    /**
     * clientAccessManagement
     *
     * @return \Jalismrs\Stalactite\Client\AccessManagement\Client
     */
    public function clientAccessManagement() : AccessManagement\Client
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
     * clientAuthentification
     *
     * @return \Jalismrs\Stalactite\Client\Authentication\Client
     */
    public function clientAuthentification() : Authentication\Client
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
     * clientDataManagement
     *
     * @return \Jalismrs\Stalactite\Client\DataManagement\Client
     */
    public function clientDataManagement() : DataManagement\Client
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
