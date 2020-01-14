<?php
declare(strict_types = 1);

namespace jalismrs\Stalactite\Client\DataManagement\Customer;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Rule\JsonRule;
use hunomina\Validator\Json\Schema\JsonSchema;
use jalismrs\Stalactite\Client\AbstractClient;
use jalismrs\Stalactite\Client\ClientException;
use jalismrs\Stalactite\Client\DataManagement\Model\ModelFactory;
use jalismrs\Stalactite\Client\DataManagement\Schema;
use jalismrs\Stalactite\Client\Response;

class MeClient extends
    AbstractClient
{
    public const API_URL_PREFIX = CustomerClient::API_URL_PREFIX . '/me';
    
    /**
     * @param string $jwt
     *
     * @return Response
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function get(string $jwt) : Response
    {
        $schema = new JsonSchema();
        $schema->setSchema(
            [
                'success' => ['type' => JsonRule::BOOLEAN_TYPE],
                'error'   => [
                    'type' => JsonRule::STRING_TYPE,
                    'null' => true
                ],
                'me'      => [
                    'type'   => JsonRule::OBJECT_TYPE,
                    'null'   => true,
                    'schema' => Schema::CUSTOMER
                ]
            ]
        );
    
        $r = $this->requestGet(
            $this->apiHost . self::API_URL_PREFIX,
            [
                'headers' => ['X-API-TOKEN' => $jwt]
            ],
            $schema
        );
        
        return (new Response())
            ->setSuccess($r['success'])
            ->setError($r['error'])
            ->setData(
                [
                    'me' => $r['me']
                        ? ModelFactory::createCustomer($r['me'])
                        : null
                ]
            );
    }
}
