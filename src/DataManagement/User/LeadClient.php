<?php

namespace jalismrs\Stalactite\Client\DataManagement\User;

use jalismrs\Stalactite\Client\AbstractClient;
use jalismrs\Stalactite\Client\DataManagement\Model\Post;
use jalismrs\Stalactite\Client\DataManagement\Model\User;

class LeadClient extends AbstractClient
{
    public const API_URL_PREFIX = '/leads';

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
     * @param array|Post[] $leads
     * @param string $jwt
     * @return array
     */
    public function addLeads(User $user, array $leads, string $jwt): array
    {

    }

    /**
     * @param User $user
     * @param array|Post[] $leads
     * @param string $jwt
     * @return array
     */
    public function deleteLeads(User $user, array $leads, string $jwt): array
    {

    }
}