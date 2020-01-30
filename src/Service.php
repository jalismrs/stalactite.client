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
    public function access(): Access\Service
    {
        if (null === $this->serviceAccess) {
            $this->serviceAccess = new Access\Service($this->getHost());
            $this->serviceAccess
                ->setHttpClient($this->getHttpClient())
                ->setLogger($this->getLogger())
                ->setUserAgent($this->getUserAgent());
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
        if (null === $this->serviceAuthentication) {
            $this->serviceAuthentication = new Authentication\Service($this->getHost());
            $this->serviceAuthentication
                ->setHttpClient($this->getHttpClient())
                ->setLogger($this->getLogger())
                ->setUserAgent($this->getUserAgent());
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
        if (null === $this->serviceData) {
            $this->serviceData = new Data\Service($this->getHost());
            $this->serviceData
                ->setHttpClient($this->getHttpClient())
                ->setLogger($this->getLogger())
                ->setUserAgent($this->getUserAgent());
        }

        return $this->serviceData;
    }
}
