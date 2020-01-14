<?php
declare(strict_types = 1);

namespace jalismrs\Stalactite\Client;

/**
 * Client
 *
 * @package jalismrs\Stalactite\Client
 */
class Client extends
    AbstractClient
{
    /**
     * auth
     *
     * @return \jalismrs\Stalactite\Client\Authentication\Client
     */
    public function auth() : Authentication\Client
    {
        static $authClient = null;
        
        if (null === $authClient) {
            $authClient = (new Authentication\Client(
                $this->apiHost,
                $this->userAgent
            ))
                ->setHttpClient($this->getHttpClient());
        }
        
        return $authClient;
    }
    
    /**
     * data
     *
     * @return \jalismrs\Stalactite\Client\DataManagement\Client
     */
    public function data() : DataManagement\Client
    {
        static $dataManagementClient = null;
        
        if (null === $dataManagementClient) {
            $dataManagementClient = (new DataManagement\Client(
                $this->apiHost,
                $this->userAgent
            ))
                ->setHttpClient($this->getHttpClient());
        }
        
        return $dataManagementClient;
    }
    
    /**
     * access
     *
     * @return \jalismrs\Stalactite\Client\AccessManagement\Client
     */
    public function access() : AccessManagement\Client
    {
        static $accessManagementClient = null;
        
        if (null === $accessManagementClient) {
            $accessManagementClient = (new AccessManagement\Client(
                $this->apiHost,
                $this->userAgent
            ))
                ->setHttpClient($this->getHttpClient());
        }
        
        return $accessManagementClient;
    }
}
