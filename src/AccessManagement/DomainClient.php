<?php

namespace jalismrs\Stalactite\Client\AccessManagement;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Rule\JsonRule;
use hunomina\Validator\Json\Schema\JsonSchema;
use jalismrs\Stalactite\Client\AbstractClient;
use jalismrs\Stalactite\Client\AccessManagement\Model\ModelFactory;
use jalismrs\Stalactite\Client\ClientException;
use jalismrs\Stalactite\Client\DataManagement\Model\Domain;
use jalismrs\Stalactite\Client\DataManagement\Schema as DataManagementSchema;
use jalismrs\Stalactite\Client\Response;

class DomainClient extends AbstractClient
{
    public const API_URL_PREFIX = Client::API_URL_PREFIX . '/domains';

    /**
     * @param Domain $domain
     * @param string $jwt
     * @return Response
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     * @throws ClientException
     */
    public function getRelations(Domain $domain, string $jwt): Response
    {
        $schema = new JsonSchema();
        $schema->setSchema([
            'success' => ['type' => JsonRule::BOOLEAN_TYPE],
            'error' => ['type' => JsonRule::STRING_TYPE, 'null' => true],
            'relations' => ['type' => JsonRule::OBJECT_TYPE, 'schema' => [
                'users' => ['type' => JsonRule::LIST_TYPE, 'schema' => [
                    'uid' => ['type' => JsonRule::STRING_TYPE],
                    'user' => ['type' => JsonRule::OBJECT_TYPE, 'schema' => DataManagementSchema::MINIMAL_USER]
                ]],
                'customers' => ['type' => JsonRule::LIST_TYPE, 'schema' => [
                    'uid' => ['type' => JsonRule::STRING_TYPE],
                    'customer' => ['type' => JsonRule::OBJECT_TYPE, 'schema' => DataManagementSchema::CUSTOMER]
                ]]
            ]]
        ]);

        $r = $this->request('GET', $this->apiHost . self::API_URL_PREFIX . '/' . $domain->getUid() . '/relations', [
            'headers' => ['X-API-TOKEN' => $jwt]
        ], $schema);

        $userRelations = [];
        foreach ($r['relations']['users'] as $relation) {
            $userRelations[] = ModelFactory::createDomainUserRelation($relation)->setDomain($domain);
        }

        $customerRelations = [];
        foreach ($r['relations']['customers'] as $relation) {
            $customerRelations[] = ModelFactory::createDomainCustomerRelation($relation)->setDomain($domain);
        }

        $response = new Response();
        $response->setSuccess($r['success'])->setError($r['error'])->setData([
            'relations' => [
                'users' => $userRelations,
                'customers' => $customerRelations
            ]
        ]);

        return $response;
    }
}