<?php

namespace jalismrs\Stalactite\Client\DataManagement;

use jalismrs\Stalactite\Client\AbstractClient;
use jalismrs\Stalactite\Client\DataManagement\Model\CertificationType;

class CertificationTypeClient extends AbstractClient
{
    public const API_URL_PREFIX = Client::API_URL_PREFIX . '/certification/types';

    /**
     * @param string $jwt
     * @return array
     */
    public function getAll(string $jwt): array
    {

    }

    /**
     * @param CertificationType $certificationType
     * @param string $jwt
     * @return array
     */
    public function get(CertificationType $certificationType, string $jwt): array
    {

    }

    /**
     * @param CertificationType $certificationType
     * @param string $jwt
     * @return array
     */
    public function create(CertificationType $certificationType, string $jwt): array
    {

    }

    /**
     * @param CertificationType $certificationType
     * @param string $jwt
     * @return array
     */
    public function update(CertificationType $certificationType, string $jwt): array
    {

    }

    /**
     * @param CertificationType $certificationType
     * @param string $jwt
     * @return array
     */
    public function delete(CertificationType $certificationType, string $jwt): array
    {

    }
}