<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Access\AuthToken\Domain;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Rule\JsonRule;
use Jalismrs\Stalactite\Client\AbstractService;
use Jalismrs\Stalactite\Client\Access\AuthToken\JwtFactory;
use Jalismrs\Stalactite\Client\Access\Model\DomainCustomerRelation;
use Jalismrs\Stalactite\Client\Access\Model\DomainUserRelation;
use Jalismrs\Stalactite\Client\Access\Model\ModelFactory;
use Jalismrs\Stalactite\Client\Client;
use Jalismrs\Stalactite\Client\ClientException;
use Jalismrs\Stalactite\Client\Data\Model\Domain;
use Jalismrs\Stalactite\Client\Data\Schema as DataSchema;
use Jalismrs\Stalactite\Client\Response;

/**
 * Service
 *
 * @package Jalismrs\Stalactite\Service\Access\AuthToken\Domain
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
            'deleteRelationsByDomain' => [
                'endpoint' => '/access/auth-token/domains/%s/relations',
                'method'   => 'DELETE',
            ],
            'getRelations'            => [
                'endpoint' => '/access/auth-token/domains/%s/relations',
                'method'   => 'GET',
                'schema'   => [
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
                                        'type' => JsonRule::STRING_TYPE
                                    ],
                                    'customer' => [
                                        'type'   => JsonRule::OBJECT_TYPE,
                                        'schema' => DataSchema::CUSTOMER,
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }
    
    /**
     * deleteRelationsByDomain
     *
     * @param Domain $domainModel
     * @param string $apiAuthToken
     *
     * @return Response
     *
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function deleteRelationsByDomain(
        Domain $domainModel,
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
                $this->requestConfigurations['deleteRelationsByDomain'],
                [
                    $domainModel->getUid(),
                ],
                [
                    'headers' => [
                        'X-API-TOKEN' => (string)$jwt
                    ]
                ]
            );
        
        return (new Response(
            $response['success'],
            $response['error']
        ));
    }
    
    /**
     * getRelations
     *
     * @param Domain $domainModel
     * @param string $apiAuthToken
     *
     * @return Response
     *
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function getRelations(
        Domain $domainModel,
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
                $this->requestConfigurations['getRelations'],
                [
                    $domainModel->getUid(),
                ],
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
            ]
        );
    }
}
