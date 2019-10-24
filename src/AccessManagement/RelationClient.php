<?php

namespace jalismrs\Stalactite\Client\AccessManagement;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Rule\JsonRule;
use hunomina\Validator\Json\Schema\JsonSchema;
use jalismrs\Stalactite\Client\AbstractClient;
use jalismrs\Stalactite\Client\AccessManagement\Model\DomainRelation;
use jalismrs\Stalactite\Client\ClientException;
use jalismrs\Stalactite\Client\Response;

class RelationClient extends AbstractClient
{
    public const API_URL_PREFIX = Client::API_URL_PREFIX . '/relations';

    /**
     * @param DomainRelation $domainRelation
     * @param string $jwt
     * @return Response
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     * @throws ClientException
     */
    public function deleteRelation(DomainRelation $domainRelation, string $jwt): Response
    {
        $schema = new JsonSchema();
        $schema->setSchema([
            'success' => ['type' => JsonRule::BOOLEAN_TYPE],
            'error' => ['type' => JsonRule::STRING_TYPE, 'null' => true]
        ]);

        $r = $this->request('DELETE', $this->apiHost . self::API_URL_PREFIX . '/' . $domainRelation->getUid(), [
            'headers' => ['X-API-TOKEN' => $jwt]
        ], $schema);

        $response = new Response();
        $response->setSuccess($r['success'])->setError($r['error']);

        return $response;
    }
}