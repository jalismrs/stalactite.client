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
    private $clientAccess;
    private $clientAuthentication;
    private $clientData;

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
        if (null === $this->clientAccess) {
            $this->clientAccess = new Access\Service($this->getHost());
            $this->clientAccess
                ->setHttpClient($this->getHttpClient())
                ->setLogger($this->getLogger())
                ->setUserAgent($this->getUserAgent());
        }

        return $this->clientAccess;
    }

    /**
     * authentication
     *
     * @return Authentication\Service
     */
    public function authentication(): Authentication\Service
    {
        if (null === $this->clientAuthentication) {
            $this->clientAuthentication = new Authentication\Service($this->getHost());
            $this->clientAuthentication
                ->setHttpClient($this->getHttpClient())
                ->setLogger($this->getLogger())
                ->setUserAgent($this->getUserAgent());
        }

        return $this->clientAuthentication;
    }

    /**
     * data
     *
     * @return Data\Service
     */
    public function data(): Data\Service
    {
        if (null === $this->clientData) {
            $this->clientData = new Data\Service($this->getHost());
            $this->clientData
                ->setHttpClient($this->getHttpClient())
                ->setLogger($this->getLogger())
                ->setUserAgent($this->getUserAgent());
        }

        return $this->clientData;
    }
}
