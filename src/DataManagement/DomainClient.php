<?php

namespace jalismrs\Stalactite\Client\DataManagement;

use jalismrs\Stalactite\Client\AbstractClient;
use jalismrs\Stalactite\Client\DataManagement\Model\Domain;

class DomainClient extends AbstractClient
{
    public const API_URL_PREFIX = Client::API_URL_PREFIX . '/domains';

    /**
     * @param string $jwt
     * @return array
     */
    public function getAll(string $jwt): array
    {

    }

    /**
     * @param Domain $domain
     * @param string $jwt
     * @return array
     */
    public function get(Domain $domain, string $jwt): array
    {

    }

    /**
     * @param Domain $domain
     * @param string $jwt
     * @return array
     */
    public function create(Domain $domain, string $jwt): array
    {

    }

    /**
     * @param Domain $domain
     * @param string $jwt
     * @return array
     */
    public function update(Domain $domain, string $jwt): array
    {

    }

    /**
     * @param Domain $domain
     * @param string $jwt
     * @return array
     */
    public function delete(Domain $domain, string $jwt): array
    {

    }
}