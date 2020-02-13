<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Data\Customer;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Rule\JsonRule;
use Jalismrs\Stalactite\Client\AbstractService;
use Jalismrs\Stalactite\Client\Data\Model\Customer;
use Jalismrs\Stalactite\Client\Data\Model\ModelFactory;
use Jalismrs\Stalactite\Client\Data\Schema;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\RequestException;
use Jalismrs\Stalactite\Client\Exception\SerializerException;
use Jalismrs\Stalactite\Client\Util\Response;
use Jalismrs\Stalactite\Client\Util\Request;
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
    
    /*
     * -------------------------------------------------------------------------
     * Clients -----------------------------------------------------------------
     * -------------------------------------------------------------------------
     */
    /**
     * me
     *
     * @return Me\Service
     *
     * @throws RequestException
     */
    public function me() : Me\Service
    {
        if ($this->serviceMe === null) {
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
     * @throws SerializerException
     */
    public function getAllCustomers(
        string $jwt
    ) : Response {
        return $this
            ->getClient()
            ->request(
                (new Request(
                    '/data/customers'
                ))
                    ->setOptions(
                        [
                            'headers' => [
                                'X-API-TOKEN' => $jwt
                            ]
                        ]
                    )
                    ->setResponse(
                        static function(array $response) : array {
                            return [
                                'customers' => array_map(
                                    static function($customer) {
                                        return ModelFactory::createCustomer($customer);
                                    },
                                    $response['customers']
                                ),
                            ];
                        }
                    )
                    ->setValidation(
                        [
                            'customers' => [
                                'type'   => JsonRule::LIST_TYPE,
                                'schema' => Schema::CUSTOMER,
                            ],
                        ]
                    )
            );
    }
    
    /**
     * getCustomer
     *
     * @param string $uid
     * @param string $jwt
     *
     * @return Response
     *
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     * @throws SerializerException
     */
    public function getCustomer(
        string $uid,
        string $jwt
    ) : Response {
        return $this
            ->getClient()
            ->request(
                (new Request(
                    '/data/customers/%s'
                ))
                    ->setOptions(
                        [
                            'headers' => [
                                'X-API-TOKEN' => $jwt
                            ]
                        ]
                    )
                    ->setResponse(
                        static function(array $response) : array {
                            return [
                                'customer' => $response['customer'] === null
                                    ? null
                                    : ModelFactory::createCustomer($response['customer']),
                            ];
                        }
                    )
                    ->setUriDatas(
                        [
                            $uid,
                        ]
                    )
                    ->setValidation(
                        [
                            'customer' => [
                                'type'   => JsonRule::OBJECT_TYPE,
                                'null'   => true,
                                'schema' => Schema::CUSTOMER,
                            ],
                        ]
                    )
            );
    }
    
    /**
     * getByEmailAndGoogleId
     *
     * @param string $email
     * @param string $googleId
     * @param string $jwt
     *
     * @return Response
     *
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     * @throws SerializerException
     */
    public function getByEmailAndGoogleId(
        string $email,
        string $googleId,
        string $jwt
    ) : Response {
        return $this
            ->getClient()
            ->request(
                (new Request(
                    '/data/customers'
                ))
                    ->setOptions(
                        [
                            'headers' => [
                                'X-API-TOKEN' => $jwt
                            ],
                            'query'   => [
                                'email'    => $email,
                                'googleId' => $googleId
                            ]
                        ]
                    )
                    ->setResponse(
                        static function(array $response) : array {
                            return [
                                'customer' => $response['customer'] === null
                                    ? null
                                    : ModelFactory::createCustomer($response['customer']),
                            ];
                        }
                    )
                    ->setValidation(
                        [
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
                        ]
                    )
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
                (new Request(
                    '/data/customers'
                ))
                    ->setMethod('POST')
                    ->setNormalization(
                        [
                            AbstractNormalizer::GROUPS => [
                                'create',
                            ],
                        ]
                    )
                    ->setOptions(
                        [
                            'headers' => [
                                'X-API-TOKEN' => $jwt
                            ],
                            'json'    => $customerModel,
                        ]
                    )
                    ->setResponse(
                        static function(array $response) : array {
                            return [
                                'customer' => $response['customer'] === null
                                    ? null
                                    : ModelFactory::createCustomer($response['customer']),
                            ];
                        }
                    )
                    ->setValidation(
                        [
                            'customer' => [
                                'type'   => JsonRule::OBJECT_TYPE,
                                'null'   => true,
                                'schema' => Schema::CUSTOMER,
                            ],
                        ]
                    )
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
                (new Request(
                    '/data/customers/%s'
                ))
                    ->setMethod('PUT')
                    ->setNormalization(
                        [
                            AbstractNormalizer::GROUPS => [
                                'update',
                            ],
                        ]
                    )
                    ->setOptions(
                        [
                            'headers' => [
                                'X-API-TOKEN' => $jwt
                            ],
                            'json'    => $customerModel,
                        ]
                    )
                    ->setUriDatas(
                        [
                            $customerModel->getUid(),
                        ]
                    )
            );
    }
    
    /**
     * deleteCustomer
     *
     * @param string $uid
     * @param string $jwt
     *
     * @return Response
     *
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     * @throws SerializerException
     */
    public function deleteCustomer(
        string $uid,
        string $jwt
    ) : Response {
        return $this
            ->getClient()
            ->request(
                (new Request(
                    '/data/customers/%s'
                ))
                    ->setMethod('DELETE')
                    ->setOptions(
                        [
                            'headers' => [
                                'X-API-TOKEN' => $jwt
                            ]
                        ]
                    )
                    ->setUriDatas(
                        [
                            $uid,
                        ]
                    )
            );
    }
}
