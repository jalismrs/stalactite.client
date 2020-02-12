<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Data\AuthToken\Customer;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Rule\JsonRule;
use Jalismrs\Stalactite\Client\AbstractService;
use Jalismrs\Stalactite\Client\Client;
use Jalismrs\Stalactite\Client\ClientException;
use Jalismrs\Stalactite\Client\Data\AuthToken\JwtFactory;
use Jalismrs\Stalactite\Client\Data\Model\ModelFactory;
use Jalismrs\Stalactite\Client\Data\Schema;
use Jalismrs\Stalactite\Client\Response;
use function array_map;

/**
 * Service
 *
 * @package Jalismrs\Stalactite\Service\Data\AuthToken\Customer
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
            'getAll'                => [
                'endpoint'   => '/data/auth-token/customers',
                'method'     => 'GET',
                'response'   => static function(array $response) : array {
                    return [
                        'customers' => array_map(
                            static function($customer) {
                                return ModelFactory::createCustomer($customer);
                            },
                            $response['customers']
                        ),
                    ];
                },
                'validation' => [
                    'customers' => [
                        'type'   => JsonRule::LIST_TYPE,
                        'schema' => Schema::CUSTOMER,
                    ],
                ],
            ],
            'getByEmailAndGoogleId' => [
                'endpoint'   => '/data/auth-token/customers',
                'method'     => 'GET',
                'response'   => static function(array $response) : array {
                    return [
                        'customer' => $response['customer'] === null
                            ? null
                            : ModelFactory::createCustomer($response['customer']),
                    ];
                },
                'validation' => [
                    'customer' => [
                        'type'   => JsonRule::OBJECT_TYPE,
                        'null'   => true,
                        'schema' => Schema::CUSTOMER,
                    ],
                ],
            ],
            'get'                   => [
                'endpoint'   => '/data/auth-token/customers/%s',
                'method'     => 'GET',
                'response'   => static function(array $response) : array {
                    return [
                        'customer' => $response['customer'] === null
                            ? null
                            : ModelFactory::createCustomer($response['customer']),
                    ];
                },
                'validation' => [
                    'customer' => [
                        'type'   => JsonRule::OBJECT_TYPE,
                        'null'   => true,
                        'schema' => Schema::CUSTOMER,
                    ],
                ],
            ],
        ];
    }
    
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
    
        return $this
            ->getClient()
            ->request(
                $this->requestConfigurations['getAll'],
                [],
                [
                    'headers' => [
                        'X-API-TOKEN' => (string)$jwt
                    ]
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
    
        return $this
            ->getClient()
            ->request(
                $this->requestConfigurations['getByEmailAndGoogleId'],
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
    
        return $this
            ->getClient()
            ->request(
                $this->requestConfigurations['get'],
                [
                    $uid,
                ],
                [
                    'headers' => [
                        'X-API-TOKEN' => (string)$jwt
                    ]
                ]
            );
    }
}
