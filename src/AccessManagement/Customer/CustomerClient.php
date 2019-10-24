<?php

namespace jalismrs\Stalactite\Client\AccessManagement\Customer;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Rule\JsonRule;
use hunomina\Validator\Json\Schema\JsonSchema;
use jalismrs\Stalactite\Client\AbstractClient;
use jalismrs\Stalactite\Client\AccessManagement\Client;
use jalismrs\Stalactite\Client\AccessManagement\Model\ModelFactory;
use jalismrs\Stalactite\Client\ClientException;
use jalismrs\Stalactite\Client\DataManagement\Model\Customer;
use jalismrs\Stalactite\Client\DataManagement\Schema;
use jalismrs\Stalactite\Client\Response;

class CustomerClient extends AbstractClient
{
    public const API_URL_PREFIX = Client::API_URL_PREFIX . '/customers';

    /**
     * @param Customer $customer
     * @param string $jwt
     * @return Response
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     * @throws ClientException
     */
    public function getRelations(Customer $customer, string $jwt): Response
    {
        $schema = new JsonSchema();
        $schema->setSchema([
            'success' => ['type' => JsonRule::BOOLEAN_TYPE],
            'error' => ['type' => JsonRule::STRING_TYPE, 'null' => true],
            'relations' => ['type' => JsonRule::LIST_TYPE, 'schema' => [
                'uid' => ['type' => JsonRule::STRING_TYPE],
                'domain' => ['type' => JsonRule::OBJECT_TYPE, 'schema' => Schema::DOMAIN]
            ]]
        ]);

        $r = $this->request('GET', $this->apiHost . self::API_URL_PREFIX . '/' . $customer->getUid() . '/relations', [
            'headers' => ['X-API-TOKEN' => $jwt]
        ], $schema);

        $relations = [];
        foreach ($r['relations'] as $relation) {
            $relations[] = ModelFactory::createDomainCustomerRelation($relation)->setCustomer($customer);
        }

        $response = new Response();
        $response->setSuccess($r['success'])->setError($r['error'])->setData([
            'relations' => $relations
        ]);

        return $response;
    }
}