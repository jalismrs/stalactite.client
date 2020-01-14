<?php
declare(strict_types = 1);

namespace jalismrs\Stalactite\Client\DataManagement\AuthToken;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Rule\JsonRule;
use hunomina\Validator\Json\Schema\JsonSchema;
use jalismrs\Stalactite\Client\AbstractClient;
use jalismrs\Stalactite\Client\ClientException;
use jalismrs\Stalactite\Client\DataManagement\Model\ModelFactory;
use jalismrs\Stalactite\Client\DataManagement\Schema;
use jalismrs\Stalactite\Client\Response;

class CustomerClient extends
    AbstractClient
{
    public const API_URL_PREFIX = AuthTokenClient::API_URL_PREFIX . '/customers';
    
    /**
     * @param string $apiAuthToken
     *
     * @return Response
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function getAll(string $apiAuthToken) : Response
    {
        $jwt = AuthTokenClient::generateJwt($apiAuthToken, $this->userAgent);
        
        $schema = new JsonSchema();
        $schema->setSchema(
            [
                'success'   => ['type' => JsonRule::BOOLEAN_TYPE],
                'error'     => [
                    'type' => JsonRule::STRING_TYPE,
                    'null' => true
                ],
                'customers' => [
                    'type'   => JsonRule::LIST_TYPE,
                    'schema' => Schema::CUSTOMER
                ]
            ]
        );
    
        $r = $this->requestGet(
            $this->apiHost . self::API_URL_PREFIX,
            [
                'headers' => ['X-API-TOKEN' => (string)$jwt]
            ],
            $schema
        );
        
        $customers = [];
        foreach ($r['customers'] as $customer) {
            $customers[] = ModelFactory::createCustomer($customer);
        }
        
        return (new Response())
            ->setSuccess($r['success'])
            ->setError($r['error'])
            ->setData(
                [
                    'customers' => $customers
                ]
            );
    }
    
    /**
     * @param string $email
     * @param string $googleId
     * @param string $apiAuthToken
     *
     * @return Response
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function getByEmailAndGoogleId(string $email, string $googleId, string $apiAuthToken) : Response
    {
        $jwt = AuthTokenClient::generateJwt($apiAuthToken, $this->userAgent);
        
        $schema = new JsonSchema();
        $schema->setSchema(
            [
                'success'  => ['type' => JsonRule::BOOLEAN_TYPE],
                'error'    => [
                    'type' => JsonRule::STRING_TYPE,
                    'null' => true
                ],
                'customer' => [
                    'type'   => JsonRule::OBJECT_TYPE,
                    'null'   => true,
                    'schema' => Schema::CUSTOMER
                ]
            ]
        );
    
        $r = $this->requestGet(
            $this->apiHost . self::API_URL_PREFIX,
            [
                'headers' => ['X-API-TOKEN' => (string)$jwt],
                'query'   => [
                    'email'    => $email,
                    'googleId' => $googleId
                ]
            ],
            $schema
        );
        
        return (new Response())
            ->setSuccess($r['success'])
            ->setError($r['error'])
            ->setData(
                [
                    'customer' => $r['customer']
                        ? ModelFactory::createCustomer($r['customer'])
                        : null
                ]
            );
    }
    
    /**
     * @param string $uid
     * @param string $apiAuthToken
     *
     * @return Response
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function get(string $uid, string $apiAuthToken) : Response
    {
        $jwt = AuthTokenClient::generateJwt($apiAuthToken, $this->userAgent);
        
        $schema = new JsonSchema();
        $schema->setSchema(
            [
                'success'  => ['type' => JsonRule::BOOLEAN_TYPE],
                'error'    => [
                    'type' => JsonRule::STRING_TYPE,
                    'null' => true
                ],
                'customer' => [
                    'type'   => JsonRule::OBJECT_TYPE,
                    'null'   => true,
                    'schema' => Schema::CUSTOMER
                ]
            ]
        );
    
        $r = $this->requestGet(
            $this->apiHost . self::API_URL_PREFIX . '/' . $uid,
            [
                'headers' => ['X-API-TOKEN' => (string)$jwt]
            ],
            $schema
        );
        
        return (new Response())
            ->setSuccess($r['success'])
            ->setError($r['error'])
            ->setData(
                [
                    'customer' => $r['customer']
                        ? ModelFactory::createCustomer($r['customer'])
                        : null
                ]
            );
    }
}
