<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client;

/**
 * Service
 *
 * @package Jalismrs\Stalactite\Service
 */
class Service extends
    AbstractService
{
    private $serviceAccess;
    private $serviceAuthentication;
    private $serviceData;
    
    /*
     * -------------------------------------------------------------------------
     * Clients -----------------------------------------------------------------
     * -------------------------------------------------------------------------
     */
    
    /**
     * access
     *
     * @return Access\Service
     */
    public function access() : Access\Service
    {
        if ($this->serviceAccess === null) {
            $this->serviceAccess = new Access\Service($this->getClient());
        }
        
        return $this->serviceAccess;
    }
    
    /**
     * authentication
     *
     * @return Authentication\Service
     *
     * @throws Exception\RequestException
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
     * @return Data\Service
     */
    public function data() : Data\Service
    {
        if ($this->serviceData === null) {
            $this->serviceData = new Data\Service($this->getClient());
        }
        
        return $this->serviceData;
    }
}
