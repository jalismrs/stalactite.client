<?php

namespace jalismrs\Stalactite\Client\DataManagement\Customer;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Rule\JsonRule;
use hunomina\Validator\Json\Schema\JsonSchema;
use jalismrs\Stalactite\Client\AbstractClient;
use jalismrs\Stalactite\Client\ClientException;
use jalismrs\Stalactite\Client\DataManagement\Client;
use jalismrs\Stalactite\Client\DataManagement\Model\Customer;
use jalismrs\Stalactite\Client\DataManagement\Model\ModelFactory;
use jalismrs\Stalactite\Client\DataManagement\Schema;
use jalismrs\Stalactite\Client\Response;

class CustomerClient extends AbstractClient
{
    public const API_URL_PREFIX = Client::API_URL_PREFIX . '/customers';

    /** @var MeClient $meClient */
    private $meClient;

    /**
     * @return MeClient
     */
    public function me(): MeClient
    {
        if (!($this->meClient instanceof MeClient)) {
            $this->meClient = new MeClient($this->apiHost, $this->userAgent);
            $this->meClient->setHttpClient($this->getHttpClient());
        }

        return $this->meClient;
    }

    /**
     * @param string $jwt
     * @return Response
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function getAll(string $jwt): Response
    {
        $schema = new JsonSchema();
        $schema->setSchema([
            'success' => ['type' => JsonRule::BOOLEAN_TYPE],
            'error' => ['type' => JsonRule::STRING_TYPE, 'null' => true],
            'customers' => ['type' => JsonRule::LIST_TYPE, 'schema' => Schema::CUSTOMER]
        ]);

        $r = $this->request('GET', $this->apiHost . self::API_URL_PREFIX, [
            'headers' => ['X-API-TOKEN' => $jwt]
        ], $schema);

        $customers = [];
        foreach ($r['customers'] as $customer) {
            $customers[] = ModelFactory::createCustomer($customer);
        }

        $response = new Response();
        $response->setSuccess($r['success'])->setError($r['error'])->setData([
            'customers' => $customers
        ]);

        return $response;
    }

    /**
     * @param string $uid
     * @param string $jwt
     * @return Response
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function get(string $uid, string $jwt): Response
    {
        $schema = new JsonSchema();
        $schema->setSchema([
            'success' => ['type' => JsonRule::BOOLEAN_TYPE],
            'error' => ['type' => JsonRule::STRING_TYPE, 'null' => true],
            'customer' => ['type' => JsonRule::OBJECT_TYPE, 'null' => true, 'schema' => Schema::CUSTOMER]
        ]);

        $r = $this->request('GET', $this->apiHost . self::API_URL_PREFIX . '/' . $uid, [
            'headers' => ['X-API-TOKEN' => $jwt]
        ], $schema);

        $response = new Response();
        $response->setSuccess($r['success'])->setError($r['error'])->setData([
            'customer' => $r['customer'] ? ModelFactory::createCustomer($r['customer']) : null
        ]);

        return $response;
    }

    /**
     * @param string $email
     * @param string $googleId
     * @param string $jwt
     * @return Response
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function getByEmailAndGoogleId(string $email, string $googleId, string $jwt): Response
    {
        $schema = new JsonSchema();
        $schema->setSchema([
            'success' => ['type' => JsonRule::BOOLEAN_TYPE],
            'error' => ['type' => JsonRule::STRING_TYPE, 'null' => true],
            'customer' => ['type' => JsonRule::OBJECT_TYPE, 'null' => true, 'schema' => Schema::CUSTOMER]
        ]);

        $r = $this->request('GET', $this->apiHost . self::API_URL_PREFIX, [
            'headers' => ['X-API-TOKEN' => $jwt],
            'query' => [
                'email' => $email,
                'googleId' => $googleId
            ]
        ], $schema);

        $response = new Response();
        $response->setSuccess($r['success'])->setError($r['error'])->setData([
            'customer' => $r['customer'] ? ModelFactory::createCustomer($r['customer']) : null
        ]);

        return $response;
    }

    /**
     * @param Customer $customer
     * @param string $jwt
     * @return Response
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function create(Customer $customer, string $jwt): Response
    {
        $body = [
            'firstName' => $customer->getFirstName(),
            'lastName' => $customer->getLastName(),
            'email' => $customer->getEmail()
        ];

        $schema = new JsonSchema();
        $schema->setSchema([
            'success' => ['type' => JsonRule::BOOLEAN_TYPE],
            'error' => ['type' => JsonRule::STRING_TYPE, 'null' => true],
            'customer' => ['type' => JsonRule::OBJECT_TYPE, 'null' => true, 'schema' => Schema::CUSTOMER]
        ]);

        $r = $this->request('POST', $this->apiHost . self::API_URL_PREFIX, [
            'headers' => ['X-API-TOKEN' => $jwt],
            'json' => $body
        ], $schema);

        $response = new Response();
        $response->setSuccess($r['success'])->setError($r['error'])->setData([
            'customer' => $r['customer'] ? ModelFactory::createCustomer($r['customer']) : null
        ]);

        return $response;
    }

    /**
     * @param Customer $customer
     * @param string $jwt
     * @return Response
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function update(Customer $customer, string $jwt): Response
    {
        $body = [
            'firstName' => $customer->getFirstName(),
            'lastName' => $customer->getLastName(),
            'email' => $customer->getEmail()
        ];

        $schema = new JsonSchema();
        $schema->setSchema([
            'success' => ['type' => JsonRule::BOOLEAN_TYPE],
            'error' => ['type' => JsonRule::STRING_TYPE, 'null' => true]
        ]);

        $r = $this->request('PUT', $this->apiHost . self::API_URL_PREFIX . '/' . $customer->getUid(), [
            'headers' => ['X-API-TOKEN' => $jwt],
            'json' => $body
        ], $schema);

        $response = new Response();
        $response->setSuccess($r['success'])->setError($r['error']);

        return $response;
    }

    /**
     * @param string $uid
     * @param string $jwt
     * @return Response
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function delete(string $uid, string $jwt): Response
    {
        $schema = new JsonSchema();
        $schema->setSchema([
            'success' => ['type' => JsonRule::BOOLEAN_TYPE],
            'error' => ['type' => JsonRule::STRING_TYPE, 'null' => true]
        ]);

        $r = $this->request('DELETE', $this->apiHost . self::API_URL_PREFIX . '/' . $uid, [
            'headers' => ['X-API-TOKEN' => $jwt]
        ], $schema);

        $response = new Response();
        $response->setSuccess($r['success'])->setError($r['error']);

        return $response;
    }
}