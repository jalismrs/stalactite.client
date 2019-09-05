<?php

namespace jalismrs\Stalactite\Client\DataManagement\User;

use jalismrs\Stalactite\Client\AbstractClient;
use jalismrs\Stalactite\Client\DataManagement\Model\PhoneLine;
use jalismrs\Stalactite\Client\DataManagement\Model\User;

class PhoneLineClient extends AbstractClient
{
    public const API_URL_PREFIX = '/phone/lines';

    /**
     * @param User $user
     * @param string $jwt
     * @return array
     */
    public function getAll(User $user, string $jwt): array
    {

    }

    /**
     * @param User $user
     * @param PhoneLine $phoneLine
     * @param string $jwt
     * @return array
     */
    public function addPhoneLine(User $user, PhoneLine $phoneLine, string $jwt): array
    {

    }

    /**
     * @param User $user
     * @param PhoneLine $phoneLine
     * @param string $jwt
     * @return array
     */
    public function removePhoneLine(User $user, PhoneLine $phoneLine, string $jwt): array
    {

    }
}