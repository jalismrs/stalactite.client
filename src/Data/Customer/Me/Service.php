<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Data\Customer\Me;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Rule\JsonRule;
use Jalismrs\Stalactite\Client\AbstractService;
use Jalismrs\Stalactite\Client\Client;
use Jalismrs\Stalactite\Client\ClientException;
use Jalismrs\Stalactite\Client\Data\Model\ModelFactory;
use Jalismrs\Stalactite\Client\Data\Schema;
use Jalismrs\Stalactite\Client\Response;

/**
 * Service
 *
 * @package Jalismrs\Stalactite\Service\Data\Customer\Me
 */
class Service extends
    AbstractService
{
    /**
     * Service constructor.
     *
     * @param Client $client
     */
    public function __construct(
        Client $client
    ) {
        parent::__construct(
            $client
        );
        
        $this->requestConfigurations = [
            'get' => [
                'endpoint'   => '/data/customers/me',
                'method'     => 'GET',
                'validation' => [
                    'me' => [
                        'type'   => JsonRule::OBJECT_TYPE,
                        'null'   => true,
                        'schema' => Schema::CUSTOMER,
                    ],
                ],
            ],
        ];
    }
    
    /**
     * @param string $jwt
     *
     * @return Response
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function getMe(
        string $jwt
    ) : Response {
        $response = $this
            ->getClient()
            ->request(
                $this->requestConfigurations['get'],
                [],
                [
                    'headers' => [
                        'X-API-TOKEN' => $jwt
                    ]
                ]
            );
        
        return new Response(
            $response['success'],
            $response['error'],
            [
                'me' => null === $response['me']
                    ? null
                    : ModelFactory::createCustomer($response['me']),
            ]
        );
    }
}
