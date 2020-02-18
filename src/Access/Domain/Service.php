<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Access\Domain;

use hunomina\DataValidator\Rule\Json\JsonRule;
use Jalismrs\Stalactite\Client\AbstractService;
use Jalismrs\Stalactite\Client\Access\Model\ModelFactory;
use Jalismrs\Stalactite\Client\Access\ResponseFactory;
use Jalismrs\Stalactite\Client\Access\Schema;
use Jalismrs\Stalactite\Client\Data\Model\Customer;
use Jalismrs\Stalactite\Client\Data\Model\Domain;
use Jalismrs\Stalactite\Client\Data\Model\User;
use Jalismrs\Stalactite\Client\Data\Schema as DataSchema;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\RequestException;
use Jalismrs\Stalactite\Client\Exception\SerializerException;
use Jalismrs\Stalactite\Client\Exception\ServiceException;
use Jalismrs\Stalactite\Client\Exception\ValidatorException;
use Jalismrs\Stalactite\Client\Util\Request;
use Jalismrs\Stalactite\Client\Util\Response;

/**
 * Service
 *
 * @package Jalismrs\Stalactite\Service\Access\Domain
 */
class Service extends
    AbstractService
{
    /**
     * getRelations
     *
     * @param Domain $domainModel
     * @param string $jwt
     *
     * @return Response
     *
     * @throws ClientException
     * @throws RequestException
     * @throws SerializerException
     * @throws ValidatorException
     */
    public function getRelations(
        Domain $domainModel,
        string $jwt
    ): Response
    {
        return $this
            ->getClient()
            ->request(
                (new Request(
                    '/access/domains/%s/relations'
                ))
                    ->setJwt($jwt)
                    ->setResponseFormatter(ResponseFactory::domainGetRelations($domainModel))
                    ->setUriParameters(
                        [
                            $domainModel->getUid(),
                        ]
                    )
                    ->setValidation(
                        [
                            'relations' => [
                                'type' => JsonRule::OBJECT_TYPE,
                                'schema' => [
                                    'users' => [
                                        'type' => JsonRule::LIST_TYPE,
                                        'schema' => [
                                            'uid' => [
                                                'type' => JsonRule::STRING_TYPE,
                                            ],
                                            'user' => [
                                                'type' => JsonRule::OBJECT_TYPE,
                                                'schema' => DataSchema::USER,
                                            ],
                                        ],
                                    ],
                                    'customers' => [
                                        'type' => JsonRule::LIST_TYPE,
                                        'schema' => [
                                            'uid' => [
                                                'type' => JsonRule::STRING_TYPE,
                                            ],
                                            'customer' => [
                                                'type' => JsonRule::OBJECT_TYPE,
                                                'schema' => DataSchema::CUSTOMER,
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ]
                    )
            );
    }
    
    /**
     * addUserRelation
     *
     * @param Domain $domainModel
     * @param User   $userModel
     * @param string $jwt
     *
     * @return Response
     *
     * @throws ClientException
     * @throws RequestException
     * @throws SerializerException
     * @throws ServiceException
     * @throws ValidatorException
     */
    public function addUserRelation(
        Domain $domainModel,
        User $userModel,
        string $jwt
    ): Response
    {
        if ($domainModel->getUid() === null) {
            throw new ServiceException(
                'Domain lacks a uid'
            );
        }
        if ($userModel->getUid() === null) {
            throw new ServiceException(
                'User lacks a uid'
            );
        }
    
        return $this
            ->getClient()
            ->request(
                (new Request(
                    '/access/domains/%s/relations/users',
                    'POST'
                ))
                    ->setJson(
                        [
                            'user' => $userModel->getUid(),
                        ]
                    )
                    ->setJwt($jwt)
                    ->setResponseFormatter(
                        static function (array $response): array {
                            return [
                                'relation' => $response['relation'] === null
                                    ? null
                                    : ModelFactory::createDomainUserRelation($response['relation']),
                            ];
                        }
                    )
                    ->setUriParameters(
                        [
                            $domainModel->getUid(),
                        ]
                    )
                    ->setValidation(
                        [
                            'relation' => [
                                'type' => JsonRule::OBJECT_TYPE,
                                'null' => true,
                                'schema' => Schema::DOMAIN_USER_RELATION,
                            ],
                        ]
                    )
            );
    }
    
    /**
     * addCustomerRelation
     *
     * @param Domain   $domainModel
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
    public function addCustomerRelation(
        Domain $domainModel,
        Customer $customerModel,
        string $jwt
    ): Response
    {
        if ($domainModel->getUid() === null) {
            throw new ServiceException(
                'Domain lacks a uid'
            );
        }
        if ($customerModel->getUid() === null) {
            throw new ServiceException(
                'Customer lacks a uid'
            );
        }
    
        return $this
            ->getClient()
            ->request(
                (new Request(
                    '/access/domains/%s/relations/customers',
                    'POST'
                ))
                    ->setJson(
                        [
                            'customer' => $customerModel->getUid(),
                        ]
                    )
                    ->setJwt($jwt)
                    ->setResponseFormatter(
                        static function (array $response): array {
                            return [
                                'relation' => $response['relation'] === null
                                    ? null
                                    : ModelFactory::createDomainCustomerRelation($response['relation']),
                            ];
                        }
                    )
                    ->setUriParameters(
                        [
                            $domainModel->getUid(),
                        ]
                    )
                    ->setValidation(
                        [
                            'relation' => [
                                'type' => JsonRule::OBJECT_TYPE,
                                'null' => true,
                                'schema' => Schema::DOMAIN_CUSTOMER_RELATION,
                            ],
                        ]
                    )
            );
    }
}
