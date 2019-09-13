<?php

namespace jalismrs\Stalactite\Client\DataManagement;

use hunomina\Validator\Json\Exception\InvalidDataException;
use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Rule\JsonRule;
use hunomina\Validator\Json\Schema\JsonSchema;
use jalismrs\Stalactite\Client\AbstractClient;
use jalismrs\Stalactite\Client\ClientException;
use jalismrs\Stalactite\Client\DataManagement\Model\CertificationType;
use jalismrs\Stalactite\Client\DataManagement\Model\ModelFactory;
use jalismrs\Stalactite\Client\Response;

class CertificationTypeClient extends AbstractClient
{
    public const API_URL_PREFIX = Client::API_URL_PREFIX . '/certification/types';

    /**
     * @param string $jwt
     * @return Response
     * @throws ClientException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function getAll(string $jwt): Response
    {
        $schema = new JsonSchema();
        $schema->setSchema([
            'success' => ['type' => JsonRule::BOOLEAN_TYPE],
            'error' => ['type' => JsonRule::STRING_TYPE, 'null' => true],
            'certificationTypes' => ['type' => JsonRule::LIST_TYPE, 'schema' => Schema::CERTIFICATION_TYPE]
        ]);

        $r = $this->request('GET', $this->apiHost . self::API_URL_PREFIX, [
            'headers' => ['X-API-TOKEN' => $jwt]
        ], $schema);

        $certificationTypes = [];
        foreach ($r['certificationTypes'] as $certificationType) {
            $certificationTypes[] = ModelFactory::createCertificationType($certificationType);
        }

        $response = new Response();
        $response->setSuccess($r['success'])->setError($r['error'])->setData([
            'certificationTypes' => $certificationTypes
        ]);

        return $response;
    }

    /**
     * @param CertificationType $certificationType
     * @param string $jwt
     * @return Response
     * @throws ClientException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function get(CertificationType $certificationType, string $jwt): Response
    {
        $schema = new JsonSchema();
        $schema->setSchema([
            'success' => ['type' => JsonRule::BOOLEAN_TYPE],
            'error' => ['type' => JsonRule::STRING_TYPE, 'null' => true],
            'certificationType' => ['type' => JsonRule::OBJECT_TYPE, 'null' => true, 'schema' => Schema::CERTIFICATION_TYPE]
        ]);

        $r = $this->request('GET', $this->apiHost . self::API_URL_PREFIX . '/' . $certificationType->getUid(), [
            'headers' => ['X-API-TOKEN' => $jwt]
        ], $schema);

        $response = new Response();
        $response->setSuccess($r['success'])->setError($r['error'])->setData([
            'certificationType' => $r['certificationType'] ? ModelFactory::createCertificationType($r['certificationType']) : null
        ]);

        return $response;
    }

    /**
     * @param CertificationType $certificationType
     * @param string $jwt
     * @return Response
     * @throws ClientException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function create(CertificationType $certificationType, string $jwt): Response
    {
        $body = [
            'name' => $certificationType->getName()
        ];

        $schema = new JsonSchema();
        $schema->setSchema([
            'success' => ['type' => JsonRule::BOOLEAN_TYPE],
            'error' => ['type' => JsonRule::STRING_TYPE, 'null' => true],
            'certificationType' => ['type' => JsonRule::OBJECT_TYPE, 'null' => true, 'schema' => Schema::CERTIFICATION_TYPE]
        ]);

        $r = $this->request('POST', $this->apiHost . self::API_URL_PREFIX, [
            'headers' => ['X-API-TOKEN' => $jwt],
            'json' => $body
        ], $schema);

        $response = new Response();
        $response->setSuccess($r['success'])->setError($r['error'])->setData([
            'certificationType' => $r['certificationType'] ? ModelFactory::createCertificationType($r['certificationType']) : null
        ]);

        return $response;
    }

    /**
     * @param CertificationType $certificationType
     * @param string $jwt
     * @return Response
     * @throws ClientException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function update(CertificationType $certificationType, string $jwt): Response
    {
        $body = ['name' => $certificationType->getName()];

        $schema = new JsonSchema();
        $schema->setSchema([
            'success' => ['type' => JsonRule::BOOLEAN_TYPE],
            'error' => ['type' => JsonRule::STRING_TYPE, 'null' => true]
        ]);

        $r = $this->request('PUT', $this->apiHost . self::API_URL_PREFIX . '/' . $certificationType->getUid(), [
            'headers' => ['X-API-TOKEN' => $jwt],
            'json' => $body
        ], $schema);

        $response = new Response();
        $response->setSuccess($r['success'])->setError($r['error']);

        return $response;
    }

    /**
     * @param CertificationType $certificationType
     * @param string $jwt
     * @return Response
     * @throws ClientException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function delete(CertificationType $certificationType, string $jwt): Response
    {
        $schema = new JsonSchema();
        $schema->setSchema([
            'success' => ['type' => JsonRule::BOOLEAN_TYPE],
            'error' => ['type' => JsonRule::STRING_TYPE, 'null' => true]
        ]);

        $r = $this->request('DELETE', $this->apiHost . self::API_URL_PREFIX . '/' . $certificationType->getUid(), [
            'headers' => ['X-API-TOKEN' => $jwt]
        ], $schema);

        $response = new Response();
        $response->setSuccess($r['success'])->setError($r['error']);

        return $response;
    }
}