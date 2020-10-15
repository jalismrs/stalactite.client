<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Authentication;

use Jalismrs\Stalactite\Client\AbstractService;

/**
 * Class Service
 *
 * @package Jalismrs\Stalactite\Client\Authentication
 */
class Service extends
    AbstractService
{
    private ?ClientApp\Service $clientAppService = null;
    private ?ServerApp\Service $serverAppService = null;
    private ?Token\Service     $tokenService     = null;
    
    /*
     * -------------------------------------------------------------------------
     * Clients -----------------------------------------------------------------
     * -------------------------------------------------------------------------
     */
    
    public function clientApps() : ClientApp\Service
    {
        if ($this->clientAppService === null) {
            $this->clientAppService = new ClientApp\Service($this->getClient());
        }
        
        return $this->clientAppService;
    }
    
    public function serverApps() : ServerApp\Service
    {
        if ($this->serverAppService === null) {
            $this->serverAppService = new ServerApp\Service($this->getClient());
        }
        
        return $this->serverAppService;
    }
    
    public function tokens() : Token\Service
    {
        if ($this->tokenService === null) {
            $this->tokenService = new Token\Service($this->getClient());
        }
        
        return $this->tokenService;
    }
}
