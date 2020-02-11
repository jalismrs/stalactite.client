<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Data\AuthToken\Customer;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Rule\JsonRule;
use hunomina\Validator\Json\Schema\JsonSchema;
use Jalismrs\Stalactite\Client\AbstractService;
use Jalismrs\Stalactite\Client\ClientException;
use Jalismrs\Stalactite\Client\Data\AuthToken\JwtFactory;
use Jalismrs\Stalactite\Client\Data\Model\ModelFactory;
use Jalismrs\Stalactite\Client\Data\Schema;
use Jalismrs\Stalactite\Client\Response;
use function array_map;
use function vsprintf;

/**
 * Service
 *
 * @package Jalismrs\Stalactite\Service\Data\AuthToken\Customer
 */
class Service extends
    AbstractService
{
    private const REQUEST_GET_ALL_CONFIGURATION                    = [
        'endpoint' => '/data/auth-token/customers',
        'method'   => 'GET',
        'schema'   => [
            'customers' => [
                'type'   => JsonRule::LIST_TYPE,
                'schema' => Schema::CUSTOMER
            ]
        ],
    ];
    private const REQUEST_GET_BY_EMAIL_AND_GOOGLE_ID_CONFIGURATION = [
        'endpoint' => '/data/auth-token/customers',
        'method'   => 'GET',
        'schema'   => [
            'customer' => [
                'type'   => JsonRule::OBJECT_TYPE,
                'null'   => true,
                'schema' => Schema::CUSTOMER
            ]
        ],
    ];
    private const REQUEST_GET_CONFIGURATION                        = [
        'endpoint' => '/data/auth-token/customers/%s',
        'method'   => 'GET',
        'schema'   => [
            'customer' => [
                'type'   => JsonRule::OBJECT_TYPE,
                'null'   => true,
                'schema' => Schema::CUSTOMER
            ]
        ],
    ];
    
    /**
     * getAllCustomers
     *
     * @param string $apiAuthToken
     *
     * @return Response
     *
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function getAllCustomers(
        string $apiAuthToken
    ) : Response {
        $jwt = JwtFactory::generateJwt(
            $apiAuthToken,
            $this
                ->getClient()
                ->getUserAgent()
        );
        
        $response = $this
            ->getClient()
            ->request(
                self::REQUEST_GET_ALL_CONFIGURATION,
                [],
                [
                    'headers' => [
                        'X-API-TOKEN' => (string)$jwt
                    ]
                ]
            );
        
        return new Response(
            $response['success'],
            $response['error'],
            [
                'customers' => array_map(
                    static function($customer) {
                        return ModelFactory::createCustomer($customer);
                    },
                    $response['customers']
                )
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
    public function getByEmailAndGoogleId(
        string $email,
        string $googleId,
        string $apiAuthToken
    ) : Response {
        $jwt = JwtFactory::generateJwt(
            $apiAuthToken,
            $this
                ->getClient()
                ->getUserAgent()
        );
        
        $response = $this
            ->getClient()
            ->request(
                self::REQUEST_GET_BY_EMAIL_AND_GOOGLE_ID_CONFIGURATION,
                [],
                [
                    'headers' => [
                        'X-API-TOKEN' => (string)$jwt
                    ],
                    'query'   => [
                        'email'    => $email,
                        'googleId' => $googleId
                    ]
                ]
            );
        
        return new Response(
            $response['success'],
            $response['error'],
            [
                'customer' => null === $response['customer']
                    ? null
                    : ModelFactory::createCustomer($response['customer']),
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
    public function getCustomer(
        string $uid,
        string $apiAuthToken
    ) : Response {
        $jwt = JwtFactory::generateJwt(
            $apiAuthToken,
            $this
                ->getClient()
                ->getUserAgent()
        );
        
        $response = $this
            ->getClient()
            ->request(
                self::REQUEST_GET_CONFIGURATION,
                [
                    $uid,
                ],
                [
                    'headers' => [
                        'X-API-TOKEN' => (string)$jwt
                    ]
                ]
            );
        
        return new Response(
            $response['success'],
            $response['error'],
            [
                'customer' => null === $response['customer']
                    ? null
                    : ModelFactory::createCustomer($response['customer']),
            ]
        );
    }
}
