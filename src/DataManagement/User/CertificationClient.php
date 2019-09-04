<?php

namespace jalismrs\Stalactite\Client\DataManagement\User;

use jalismrs\Stalactite\Client\AbstractClient;
use jalismrs\Stalactite\Client\DataManagement\Model\CertificationGraduation;
use jalismrs\Stalactite\Client\DataManagement\Model\User;

class CertificationClient extends AbstractClient
{
    public const API_URL_PREFIX = '/certifications';

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
     * @param CertificationGraduation $certificationGraduation
     * @param string $jwt
     * @return array
     */
    public function addCertificationGraduation(User $user, CertificationGraduation $certificationGraduation, string $jwt): array
    {

    }

    /**
     * @param User $user
     * @param CertificationGraduation $certificationGraduation
     * @param string $jwt
     * @return array
     */
    public function deleteCertificationGraduation(User $user, CertificationGraduation $certificationGraduation, string $jwt): array
    {

    }
}