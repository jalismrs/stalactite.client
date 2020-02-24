<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Data\Customer;

use hunomina\DataValidator\Rule\Json\JsonRule;
use Jalismrs\Stalactite\Client\AbstractService;
use Jalismrs\Stalactite\Client\Data\Model\Customer;
use Jalismrs\Stalactite\Client\Data\Model\ModelFactory;
use Jalismrs\Stalactite\Client\Data\Schema;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\RequestException;
use Jalismrs\Stalactite\Client\Exception\SerializerException;
use Jalismrs\Stalactite\Client\Exception\ServiceException;
use Jalismrs\Stalactite\Client\Exception\ValidatorException;
use Jalismrs\Stalactite\Client\Util\Request;
use Jalismrs\Stalactite\Client\Util\Response;
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
    /**
     * @var Me\Service|null
     */
    private ?Me\Service $serviceMe = null;

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
    public function me(): Me\Service
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
     * @throws RequestException
     * @throws SerializerException
     * @throws ValidatorException
     */
    public function getAllCustomers(
        string $jwt
    ): Response
    {
        return $this
            ->getClient()
            ->request(
                (new Request(
                    '/data/customers'
                ))
                    ->setJwt($jwt)
                    ->setResponse(
                        static function (array $response): array {
                            return [
                                'customers' => array_map(
                                    static function ($customer) {
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
                                'type' => JsonRule::LIST_TYPE,
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
     * @throws RequestException
     * @throws SerializerException
     * @throws ValidatorException
     */
    public function getCustomer(
        string $uid,
        string $jwt
    ): Response
    {
        return $this
            ->getClient()
            ->request(
                (new Request(
                    '/data/customers/%s'
                ))
                    ->setJwt($jwt)
                    ->setResponse(
                        static function (array $response): array {
                            return [
                                'customer' => $response['customer'] === null
                                    ? null
                                    : ModelFactory::createCustomer($response['customer']),
                            ];
                        }
                    )
                    ->setUriParameters(
                        [
                            $uid,
                        ]
                    )
                    ->setValidation(
                        [
                            'customer' => [
                                'type' => JsonRule::OBJECT_TYPE,
                                'null' => true,
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
     * @throws RequestException
     * @throws SerializerException
     * @throws ValidatorException
     */
    public function getByEmailAndGoogleId(
        string $email,
        string $googleId,
        string $jwt
    ): Response
    {
        return $this
            ->getClient()
            ->request(
                (new Request(
                    '/data/customers'
                ))
                    ->setJwt($jwt)
                    ->setQueryParameters(
                        [
                            'email' => $email,
                            'googleId' => $googleId
                        ]
                    )
                    ->setResponse(
                        static function (array $response): array {
                            return [
                                'customer' => $response['customer'] === null
                                    ? null
                                    : ModelFactory::createCustomer($response['customer']),
                            ];
                        }
                    )
                    ->setValidation(
                        [
                            'success' => [
                                'type' => JsonRule::BOOLEAN_TYPE,
                            ],
                            'error' => [
                                'type' => JsonRule::STRING_TYPE,
                                'null' => true,
                            ],
                            'customer' => [
                                'type' => JsonRule::OBJECT_TYPE,
                                'null' => true,
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
     * @throws RequestException
     * @throws SerializerException
     * @throws ServiceException
     * @throws ValidatorException
     */
    public function createCustomer(
        Customer $customerModel,
        string $jwt
    ): Response
    {
        if ($customerModel->getUid() !== null) {
            throw new ServiceException(
                'Customer has a uid'
            );
        }
    
        return $this
            ->getClient()
            ->request(
                (new Request(
                    '/data/customers',
                    'POST'
                ))
                    ->setJson($customerModel)
                    ->setJwt($jwt)
                    ->setNormalization(
                        [
                            AbstractNormalizer::GROUPS => [
                                'create',
                            ],
                        ]
                    )
                    ->setResponse(
                        static function (array $response): array {
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
                                'type' => JsonRule::OBJECT_TYPE,
                                'null' => true,
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
     * @throws RequestException
     * @throws SerializerException
     * @throws ServiceException
     * @throws ValidatorException
     */
    public function updateCustomer(
        Customer $customerModel,
        string $jwt
    ): Response
    {
        if ($customerModel->getUid() === null) {
            throw new ServiceException(
                'Customer lacks a uid'
            );
        }
    
        return $this
            ->getClient()
            ->request(
                (new Request(
                    '/data/customers/%s',
                    'PUT'
                ))
                    ->setJson($customerModel)
                    ->setJwt($jwt)
                    ->setNormalization(
                        [
                            AbstractNormalizer::GROUPS => [
                                'update',
                            ],
                        ]
                    )
                    ->setUriParameters(
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
     * @throws RequestException
     * @throws SerializerException
     * @throws ValidatorException
     */
    public function deleteCustomer(
        string $uid,
        string $jwt
    ): Response
    {
        return $this
            ->getClient()
            ->request(
                (new Request(
                    '/data/customers/%s',
                    'DELETE'
                ))
                    ->setJwt($jwt)
                    ->setUriParameters(
                        [
                            $uid,
                        ]
                    )
            );
    }
}
