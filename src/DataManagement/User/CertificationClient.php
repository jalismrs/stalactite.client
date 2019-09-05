<?php

namespace jalismrs\Stalactite\Client\DataManagement\User;

use hunomina\Validator\Json\Exception\InvalidDataException;
use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Rule\JsonRule;
use hunomina\Validator\Json\Schema\JsonSchema;
use jalismrs\Stalactite\Client\AbstractClient;
use jalismrs\Stalactite\Client\ClientException;
use jalismrs\Stalactite\Client\DataManagement\Model\CertificationGraduation;
use jalismrs\Stalactite\Client\DataManagement\Model\CertificationType;
use jalismrs\Stalactite\Client\DataManagement\Model\User;
use jalismrs\Stalactite\Client\DataManagement\Schema;

class CertificationClient extends AbstractClient
{
    public const API_URL_PREFIX = '/certifications';

    /**
     * @param User $user
     * @param string $jwt
     * @return array
     * @throws InvalidSchemaException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     * @throws ClientException
     */
    public function getAll(User $user, string $jwt): array
    {
        $schema = new JsonSchema();
        $schema->setSchema([
            'success' => ['type' => JsonRule::BOOLEAN_TYPE],
            'error' => ['type' => JsonRule::STRING_TYPE, 'null' => true],
            'certifications' => ['type' => JsonRule::LIST_TYPE, 'schema' => Schema::CERTIFICATION_GRADUATION]
        ]);

        return $this->request('GET', $this->apiHost . UserClient::API_URL_PREFIX . '/' . $user->getUid() . self::API_URL_PREFIX, [
            'headers' => ['X-API-TOKEN' => $jwt]
        ], $schema);
    }

    /**
     * @param User $user
     * @param CertificationGraduation $certificationGraduation
     * @param string $jwt
     * @return array
     * @throws ClientException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function addCertification(User $user, CertificationGraduation $certificationGraduation, string $jwt): array
    {
        if (!($certificationGraduation->getType() instanceof CertificationType)) {
            throw new ClientException('Certification Graduation type must be a Certification Type');
        }

        $body = [
            'date' => $certificationGraduation->getDate(),
            'type' => [
                'uid' => $certificationGraduation->getType()->getUid()
            ]
        ];

        $schema = new JsonSchema();
        $schema->setSchema([
            'success' => ['type' => JsonRule::BOOLEAN_TYPE],
            'error' => ['type' => JsonRule::STRING_TYPE, 'null' => true],
        ]);

        return $this->request('POST', $this->apiHost . UserClient::API_URL_PREFIX . '/' . $user->getUid() . self::API_URL_PREFIX, [
            'headers' => ['X-API-TOKEN' => $jwt],
            'json' => $body
        ], $schema);
    }

    /**
     * @param User $user
     * @param CertificationGraduation $certificationGraduation
     * @param string $jwt
     * @return array
     * @throws ClientException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function removeCertification(User $user, CertificationGraduation $certificationGraduation, string $jwt): array
    {
        $schema = new JsonSchema();
        $schema->setSchema([
            'success' => ['type' => JsonRule::BOOLEAN_TYPE],
            'error' => ['type' => JsonRule::STRING_TYPE, 'null' => true]
        ]);

        return $this->request('DELETE', $this->apiHost . UserClient::API_URL_PREFIX . '/' . $user->getUid() . self::API_URL_PREFIX . '/' . $certificationGraduation->getUid(), [
            'headers' => ['X-API-TOKEN' => $jwt]
        ], $schema);
    }
}