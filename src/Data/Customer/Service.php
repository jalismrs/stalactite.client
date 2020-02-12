<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Data\Customer;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Rule\JsonRule;
use Jalismrs\Stalactite\Client\AbstractService;
use Jalismrs\Stalactite\Client\Client;
use Jalismrs\Stalactite\Client\ClientException;
use Jalismrs\Stalactite\Client\Data\Model\Customer;
use Jalismrs\Stalactite\Client\Data\Model\ModelFactory;
use Jalismrs\Stalactite\Client\Data\Schema;
use Jalismrs\Stalactite\Client\Response;
use Jalismrs\Stalactite\Client\Util\SerializerException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use function array_map;

/**
 * Service
 *
 * @package Jalismrs\Stalactite\Service\Data\Customer
 */
class Service extends
    AbstractService
{
    private $serviceMe;
    
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
            'create'                => [
                'endpoint'      => '/data/customers',
                'method'        => 'POST',
                'normalization' => [
                    AbstractNormalizer::GROUPS => [
                        'create',
                    ],
                ],
                'response'   => static function(array $response) : array {
                    return [
                        'customer' => null === $response['customer']
                            ? null
                            : ModelFactory::createCustomer($response['customer']),
                    ];
                },
                'validation'    => [
                    'customer' => [
                        'type'   => JsonRule::OBJECT_TYPE,
                        'null'   => true,
                        'schema' => Schema::CUSTOMER,
                    ],
                ],
            ],
            'delete'                => [
                'endpoint' => '/data/customers/%s',
                'method'   => 'DELETE',
            ],
            'getAll'                => [
                'endpoint'   => '/data/customers',
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
                'endpoint'   => '/data/customers',
                'method'     => 'GET',
                'response'   => static function(array $response) : array {
                    return [
                        'customer' => $response['customer'] === null
                            ? null
                            : ModelFactory::createCustomer($response['customer']),
                    ];
                },
                'validation' => [
                    'success'  => [
                        'type' => JsonRule::BOOLEAN_TYPE,
                    ],
                    'error'    => [
                        'type' => JsonRule::STRING_TYPE,
                        'null' => true,
                    ],
                    'customer' => [
                        'type'   => JsonRule::OBJECT_TYPE,
                        'null'   => true,
                        'schema' => Schema::CUSTOMER,
                    ],
                ],
            ],
            'get'                   => [
                'endpoint'   => '/data/customers/%s',
                'method'     => 'GET',
                'response'   => static function(array $response) : array {
                    return [
                        'customer' => null === $response['customer']
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
            'update'                => [
                'endpoint'      => '/data/customers/%s',
                'method'        => 'PUT',
                'normalization' => [
                    AbstractNormalizer::GROUPS => [
                        'update',
                    ],
                ],
            ],
        ];
    }
    
    /*
     * -------------------------------------------------------------------------
     * Clients -----------------------------------------------------------------
     * -------------------------------------------------------------------------
     */
    /**
     * me
     *
     * @return Me\Service
     */
    public function me() : Me\Service
    {
        if (null === $this->serviceMe) {
            $this->serviceMe = new Me\Service($this->getClient());
        }
        
        return $this->serviceMe;
    }
    
    /*
     * -------------------------------------------------------------------------
     * API ---------------------------------------------------------------------
     * -------------------------------------------------------------------------
     */
    /**
     * getAllCustomers
     *
     * @param string $jwt
     *
     * @return Response
     *
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function getAllCustomers(
        string $jwt
    ) : Response {
        return $this
            ->getClient()
            ->request(
                $this->requestConfigurations['getAll'],
                [],
                [
                    'headers' => [
                        'X-API-TOKEN' => $jwt
                    ]
                ]
            );
    }
    
    /**
     * @param string $uid
     * @param string $jwt
     *
     * @return Response
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function getCustomer(
        string $uid,
        string $jwt
    ) : Response {
        return $this
            ->getClient()
            ->request(
                $this->requestConfigurations['get'],
                [
                    $uid,
                ],
                [
                    'headers' => [
                        'X-API-TOKEN' => $jwt
                    ]
                ]
            );
    }
    
    /**
     * @param string $email
     * @param string $googleId
     * @param string $jwt
     *
     * @return Response
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function getByEmailAndGoogleId(
        string $email,
        string $googleId,
        string $jwt
    ) : Response {
        return $this
            ->getClient()
            ->request(
                $this->requestConfigurations['getByEmailAndGoogleId'],
                [],
                [
                    'headers' => [
                        'X-API-TOKEN' => $jwt
                    ],
                    'query'   => [
                        'email'    => $email,
                        'googleId' => $googleId
                    ]
                ]
            );
    }
    
    /**
     * createCustomer
     *
     * @param Customer $customerModel
     * @param string   $jwt
     *
     * @return Response
     *
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     * @throws SerializerException
     */
    public function createCustomer(
        Customer $customerModel,
        string $jwt
    ) : Response {
        return $this
            ->getClient()
            ->request(
                $this->requestConfigurations['create'],
                [],
                [
                    'headers' => [
                        'X-API-TOKEN' => $jwt
                    ],
                    'json'    => $customerModel,
                ]
            );
    }
    
    /**
     * updateCustomer
     *
     * @param Customer $customerModel
     * @param string   $jwt
     *
     * @return Response
     *
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     * @throws SerializerException
     */
    public function updateCustomer(
        Customer $customerModel,
        string $jwt
    ) : Response {
        return $this
            ->getClient()
            ->request(
                $this->requestConfigurations['update'],
                [
                    $customerModel->getUid(),
                ],
                [
                    'headers' => [
                        'X-API-TOKEN' => $jwt
                    ],
                    'json'    => $customerModel,
                ]
            );
    }
    
    /**
     * @param string $uid
     * @param string $jwt
     *
     * @return Response
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function deleteCustomer(
        string $uid,
        string $jwt
    ) : Response {
        return $this
            ->getClient()
            ->request(
                $this->requestConfigurations['delete'],
                [
                    $uid,
                ],
                [
                    'headers' => [
                        'X-API-TOKEN' => $jwt
                    ]
                ]
            );
    }
}
