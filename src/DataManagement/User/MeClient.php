<?php

namespace jalismrs\Stalactite\Client\DataManagement\User;

use jalismrs\Stalactite\Client\AbstractClient;
use jalismrs\Stalactite\Client\DataManagement\Model\PhoneLine;
use User;

class MeClient extends AbstractClient
{
    public const API_URL_PREFIX = UserClient::API_URL_PREFIX . '/me';

    /**
     * @param string $userJwt
     * @return array
     */
    public function get(string $userJwt): array
    {

    }

    /**
     * @param User $user
     * @param string $jwt
     * @return array
     */
    public function update(User $user, string $jwt): array
    {

    }

    /**
     * @param PhoneLine $phoneLine
     * @param string $jwt
     * @return array
     */
    public function addPhoneLine(PhoneLine $phoneLine, string $jwt): array
    {

    }

    /**
     * @param PhoneLine $phoneLine
     * @param string $jwt
     * @return array
     */
    public function deletePhoneLine(PhoneLine $phoneLine, string $jwt): array
    {

    }
}