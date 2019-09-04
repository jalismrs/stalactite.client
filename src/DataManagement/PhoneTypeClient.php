<?php

namespace jalismrs\Stalactite\Client\DataManagement;

use jalismrs\Stalactite\Client\AbstractClient;
use jalismrs\Stalactite\Client\DataManagement\Model\PhoneType;

class PhoneTypeClient extends AbstractClient
{
    public const API_URL_PREFIX = Client::API_URL_PREFIX . '/phone/types';

    /**
     * @param string $jwt
     * @return array
     */
    public function getAll(string $jwt): array
    {

    }

    /**
     * @param PhoneType $phoneType
     * @param string $jwt
     * @return array
     */
    public function get(PhoneType $phoneType, string $jwt): array
    {

    }

    /**
     * @param PhoneType $phoneType
     * @param string $jwt
     * @return array
     */
    public function create(PhoneType $phoneType, string $jwt): array
    {

    }

    /**
     * @param PhoneType $phoneType
     * @param string $jwt
     * @return array
     */
    public function update(PhoneType $phoneType, string $jwt): array
    {

    }

    /**
     * @param PhoneType $phoneType
     * @param string $jwt
     * @return array
     */
    public function delete(PhoneType $phoneType, string $jwt): array
    {

    }
}