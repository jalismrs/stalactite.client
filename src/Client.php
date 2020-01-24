<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client;

/**
 * Client
 *
 * @package Jalismrs\Stalactite\Client
 */
class Client extends
    ClientAbstract
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
     * @return Access\Client
     */
    public function access(): Access\Client
    {
        if (null === $this->clientAccess) {
            $this->clientAccess = new Access\Client(
                $this->host,
                $this->userAgent,
                $this->httpClient
            );
        }

        return $this->clientAccess;
    }

    /**
     * authentication
     *
     * @return Authentication\Client
     */
    public function authentication(): Authentication\Client
    {
        if (null === $this->clientAuthentication) {
            $this->clientAuthentication = new Authentication\Client(
                $this->host,
                $this->userAgent,
                $this->httpClient
            );
        }

        return $this->clientAuthentication;
    }

    /**
     * data
     *
     * @return Data\Client
     */
    public function data(): Data\Client
    {
        if (null === $this->clientData) {
            $this->clientData = new Data\Client(
                $this->host,
                $this->userAgent,
                $this->httpClient
            );
        }

        return $this->clientData;
    }
}
