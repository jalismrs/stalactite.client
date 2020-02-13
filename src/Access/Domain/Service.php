<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Access\Domain;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Rule\JsonRule;
use Jalismrs\Stalactite\Client\AbstractService;
use Jalismrs\Stalactite\Client\Access\Model\DomainCustomerRelation;
use Jalismrs\Stalactite\Client\Access\Model\DomainUserRelation;
use Jalismrs\Stalactite\Client\Access\Model\ModelFactory;
use Jalismrs\Stalactite\Client\Access\Schema;
use Jalismrs\Stalactite\Client\Data\Model\Customer;
use Jalismrs\Stalactite\Client\Data\Model\Domain;
use Jalismrs\Stalactite\Client\Data\Model\User;
use Jalismrs\Stalactite\Client\Data\Schema as DataSchema;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\RequestException;
use Jalismrs\Stalactite\Client\Exception\SerializerException;
use Jalismrs\Stalactite\Client\Util\Request;
use Jalismrs\Stalactite\Client\Util\Response;
use function array_map;

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
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     * @throws RequestException
     * @throws SerializerException
     */
    public function getRelations(
        Domain $domainModel,
        string $jwt
    ) : Response {
        return $this
            ->getClient()
            ->request(
                (new Request(
                    '/access/domains/%s/relations'
                ))
                    ->setJwt($jwt)
                    ->setResponse(
                        static function(array $response) use ($domainModel) : array {
                            return [
                                'relations' => [
                                    'users'     => array_map(
                                        static function(array $relation) use ($domainModel): DomainUserRelation {
                                            $domainUserRelationModel = ModelFactory::createDomainUserRelation($relation);
                                            $domainUserRelationModel->setDomain($domainModel);
                                            
                                            return $domainUserRelationModel;
                                        },
                                        $response['relations']['users']
                                    ),
                                    'customers' => array_map(
                                        static function(array $relation) use ($domainModel): DomainCustomerRelation {
                                            $domainCustomerRelation = ModelFactory::createDomainCustomerRelation($relation);
                                            $domainCustomerRelation->setDomain($domainModel);
                                            
                                            return $domainCustomerRelation;
                                        },
                                        $response['relations']['customers']
                                    )
                                ]
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
                            'relations' => [
                                'type'   => JsonRule::OBJECT_TYPE,
                                'schema' => [
                                    'users'     => [
                                        'type'   => JsonRule::LIST_TYPE,
                                        'schema' => [
                                            'uid'  => [
                                                'type' => JsonRule::STRING_TYPE,
                                            ],
                                            'user' => [
                                                'type'   => JsonRule::OBJECT_TYPE,
                                                'schema' => DataSchema::USER,
                                            ],
                                        ],
                                    ],
                                    'customers' => [
                                        'type'   => JsonRule::LIST_TYPE,
                                        'schema' => [
                                            'uid'      => [
                                                'type' => JsonRule::STRING_TYPE,
                                            ],
                                            'customer' => [
                                                'type'   => JsonRule::OBJECT_TYPE,
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
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     * @throws SerializerException
     */
    public function addUserRelation(
        Domain $domainModel,
        User $userModel,
        string $jwt
    ) : Response {
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
                    ->setResponse(
                        static function(array $response) : array {
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
                                'type'   => JsonRule::OBJECT_TYPE,
                                'null'   => true,
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
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     * @throws SerializerException
     */
    public function addCustomerRelation(
        Domain $domainModel,
        Customer $customerModel,
        string $jwt
    ) : Response {
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
                    ->setResponse(
                        static function(array $response) : array {
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
                                'type'   => JsonRule::OBJECT_TYPE,
                                'null'   => true,
                                'schema' => Schema::DOMAIN_CUSTOMER_RELATION,
                            ],
                        ]
                    )
            );
    }
}
