<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client;

/**
 * Class Service
 *
 * @package Jalismrs\Stalactite\Client
 */
class Service extends
    AbstractService
{
    /**
     * serviceAuthentication
     *
     * @var \Jalismrs\Stalactite\Client\Authentication\Service|null
     */
    private ?Authentication\Service $serviceAuthentication = null;
    /**
     * serviceData
     *
     * @var \Jalismrs\Stalactite\Client\Data\Service|null
     */
    private ?Data\Service $serviceData = null;
    
    /**
     * authentication
     *
     * @return \Jalismrs\Stalactite\Client\Authentication\Service
     */
    public function authentication() : Authentication\Service
    {
        if ($this->serviceAuthentication === null) {
            $this->serviceAuthentication = new Authentication\Service($this->getClient());
        }
        
        return $this->serviceAuthentication;
    }
    
    /**
     * data
     *
     * @return \Jalismrs\Stalactite\Client\Data\Service
     */
    public function data() : Data\Service
    {
        if ($this->serviceData === null) {
            $this->serviceData = new Data\Service($this->getClient());
        }
        
        return $this->serviceData;
    }
}
