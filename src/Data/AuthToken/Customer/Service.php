<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Data\AuthToken\Customer;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Rule\JsonRule;
use Jalismrs\Stalactite\Client\AbstractService;
use Jalismrs\Stalactite\Client\Data\AuthToken\JwtFactory;
use Jalismrs\Stalactite\Client\Data\Model\ModelFactory;
use Jalismrs\Stalactite\Client\Data\Schema;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\SerializerException;
use Jalismrs\Stalactite\Client\Util\Response;
use Jalismrs\Stalactite\Client\Util\Request;
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
     * getAllCustomers
     *
     * @param string $apiAuthToken
     *
     * @return Response
     *
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     * @throws SerializerException
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
                (new Request(
                    '/data/auth-token/customers'
                ))
                    ->setOptions(
                        [
                            'headers' => [
                                'X-API-TOKEN' => (string)$jwt
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
     * getByEmailAndGoogleId
     *
     * @param string $email
     * @param string $googleId
     * @param string $apiAuthToken
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
                (new Request(
                    '/data/auth-token/customers'
                ))
                    ->setOptions(
                        [
                            'headers' => [
                                'X-API-TOKEN' => (string)$jwt
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
     * getCustomer
     *
     * @param string $uid
     * @param string $apiAuthToken
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
                (new Request(
                    '/data/auth-token/customers/%s'
                ))
                    ->setOptions(
                        [
                            'headers' => [
                                'X-API-TOKEN' => (string)$jwt
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
}
