<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client;

/**
 * Service
 *
 * @package Jalismrs\Stalactite\Service
 */
class Service extends
    AbstractService
{
    /**
     * @var Access\Service|null
     */
    private ?Access\Service $serviceAccess = null;
    /**
     * @var Authentication\Service|null
     */
    private ?Authentication\Service $serviceAuthentication = null;
    /**
     * @var Data\Service|null
     */
    private ?Data\Service $serviceData = null;

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
    public function access(): Access\Service
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
     */
    public function authentication(): Authentication\Service
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
    public function data(): Data\Service
    {
        if ($this->serviceData === null) {
            $this->serviceData = new Data\Service($this->getClient());
        }

        return $this->serviceData;
    }
}
