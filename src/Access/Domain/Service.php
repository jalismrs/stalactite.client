<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Access\Domain;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Rule\JsonRule;
use hunomina\Validator\Json\Schema\JsonSchema;
use Jalismrs\Stalactite\Client\AbstractService;
use Jalismrs\Stalactite\Client\Access\Model\DomainCustomerRelation;
use Jalismrs\Stalactite\Client\Access\Model\DomainUserRelation;
use Jalismrs\Stalactite\Client\Access\Model\ModelFactory;
use Jalismrs\Stalactite\Client\Access\Schema;
use Jalismrs\Stalactite\Client\ClientException;
use Jalismrs\Stalactite\Client\Data\Model\Customer;
use Jalismrs\Stalactite\Client\Data\Model\Domain;
use Jalismrs\Stalactite\Client\Data\Model\User;
use Jalismrs\Stalactite\Client\Data\Schema as DataSchema;
use Jalismrs\Stalactite\Client\Response;
use function array_map;
use function vsprintf;

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
     */
    public function getRelations(
        Domain $domainModel,
        string $jwt
    ): Response
    {
        $schema = new JsonSchema();
        $schema->setSchema(
            [
                'success' => [
                    'type' => JsonRule::BOOLEAN_TYPE
                ],
                'error' => [
                    'type' => JsonRule::STRING_TYPE,
                    'null' => true
                ],
                'relations' => [
                    'type' => JsonRule::OBJECT_TYPE,
                    'schema' => [
                        'users' => [
                            'type' => JsonRule::LIST_TYPE,
                            'schema' => [
                                'uid' => [
                                    'type' => JsonRule::STRING_TYPE
                                ],
                                'user' => [
                                    'type' => JsonRule::OBJECT_TYPE,
                                    'schema' => DataSchema::USER
                                ]
                            ]
                        ],
                        'customers' => [
                            'type' => JsonRule::LIST_TYPE,
                            'schema' => [
                                'uid' => [
                                    'type' => JsonRule::STRING_TYPE
                                ],
                                'customer' => [
                                    'type' => JsonRule::OBJECT_TYPE,
                                    'schema' => DataSchema::CUSTOMER
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        );

        $response = $this
            ->getClient()
            ->get(
                vsprintf(
                    '/access/domains/%s/relations',
                    [
                        $domainModel->getUid(),
                    ],
                ),
                [
                    'headers' => [
                        'X-API-TOKEN' => $jwt
                    ]
                ],
                $schema
            );

        return new Response(
            $response['success'],
            $response['error'],
            [
                'relations' => [
                    'users' => array_map(
                        static function (array $relation) use ($domainModel): DomainUserRelation {
                            $domainUserRelationModel = ModelFactory::createDomainUserRelation($relation);
                            $domainUserRelationModel->setDomain($domainModel);

                            return $domainUserRelationModel;
                        },
                        $response['relations']['users']
                    ),
                    'customers' => array_map(
                        static function (array $relation) use ($domainModel): DomainCustomerRelation {
                            $domainCustomerRelation = ModelFactory::createDomainCustomerRelation($relation);
                            $domainCustomerRelation->setDomain($domainModel);

                            return $domainCustomerRelation;
                        },
                        $response['relations']['customers']
                    )
                ]
            ]
        );
    }

    /**
     * addUserRelation
     *
     * @param Domain $domainModel
     * @param User $userModel
     * @param string $jwt
     *
     * @return Response
     *
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function addUserRelation(
        Domain $domainModel,
        User $userModel,
        string $jwt
    ): Response
    {
        $schema = new JsonSchema();
        $schema->setSchema(
            [
                'success' => [
                    'type' => JsonRule::BOOLEAN_TYPE
                ],
                'error' => [
                    'type' => JsonRule::STRING_TYPE,
                    'null' => true
                ],
                'relation' => [
                    'type' => JsonRule::OBJECT_TYPE,
                    'null' => true,
                    'schema' => Schema::DOMAIN_USER_RELATION
                ]
            ]
        );

        $response = $this
            ->getClient()
            ->post(
                vsprintf(
                    '/access/domains/%s/relations/users',
                    [
                        $domainModel->getUid(),
                    ],
                ),
                [
                    'headers' => [
                        'X-API-TOKEN' => $jwt
                    ],
                    'json' => [
                        'user' => $userModel->getUid(),
                    ],
                ],
                $schema
            );

        return new Response(
            $response['success'],
            $response['error'],
            [
                'relation' => null === $response['relation']
                    ? null
                    : ModelFactory::createDomainUserRelation($response['relation']),
            ]
        );
    }

    /**
     * addCustomerRelation
     *
     * @param Domain $domainModel
     * @param Customer $customerModel
     * @param string $jwt
     *
     * @return Response
     *
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function addCustomerRelation(
        Domain $domainModel,
        Customer $customerModel,
        string $jwt
    ): Response
    {
        $schema = new JsonSchema();
        $schema->setSchema(
            [
                'success' => [
                    'type' => JsonRule::BOOLEAN_TYPE
                ],
                'error' => [
                    'type' => JsonRule::STRING_TYPE,
                    'null' => true
                ],
                'relation' => [
                    'type' => JsonRule::OBJECT_TYPE,
                    'null' => true,
                    'schema' => Schema::DOMAIN_CUSTOMER_RELATION
                ]
            ]
        );

        $response = $this
            ->getClient()
            ->post(
                vsprintf(
                    '/access/domains/%s/relations/customers',
                    [
                        $domainModel->getUid(),
                    ],
                ),
                [
                    'headers' => [
                        'X-API-TOKEN' => $jwt
                    ],
                    'json' => [
                        'customer' => $customerModel->getUid(),
                    ],
                ],
                $schema
            );

        return new Response(
            $response['success'],
            $response['error'],
            [
                'relation' => null === $response['relation']
                    ? null
                    : ModelFactory::createDomainCustomerRelation($response['relation']),
            ]
        );
    }
}
