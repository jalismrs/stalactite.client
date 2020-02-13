<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Access\AuthToken\Domain;

use hunomina\Validator\Json\Rule\JsonRule;
use Jalismrs\Stalactite\Client\AbstractService;
use Jalismrs\Stalactite\Client\Access\AuthToken\JwtFactory;
use Jalismrs\Stalactite\Client\Access\Model\DomainCustomerRelation;
use Jalismrs\Stalactite\Client\Access\Model\DomainUserRelation;
use Jalismrs\Stalactite\Client\Access\Model\ModelFactory;
use Jalismrs\Stalactite\Client\Data\Model\Domain;
use Jalismrs\Stalactite\Client\Data\Schema as DataSchema;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\RequestException;
use Jalismrs\Stalactite\Client\Exception\SerializerException;
use Jalismrs\Stalactite\Client\Util\Request;
use Jalismrs\Stalactite\Client\Util\Response;

/**
 * Service
 *
 * @package Jalismrs\Stalactite\Service\Access\AuthToken\Domain
 */
class Service extends
    AbstractService
{
    /**
     * deleteRelationsByDomain
     *
     * @param Domain $domainModel
     * @param string $apiAuthToken
     *
     * @return Response
     *
     * @throws ClientException
     * @throws RequestException
     * @throws SerializerException
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
        
        return $this
            ->getClient()
            ->request(
                (new Request(
                    '/access/auth-token/domains/%s/relations',
                    'DELETE'
                ))
                    ->setJwt((string)$jwt)
                    ->setUriParameters(
                        [
                            $domainModel->getUid(),
                        ]
                    )
            );
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
     * @throws RequestException
     * @throws SerializerException
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
        
        return $this
            ->getClient()
            ->request(
                (new Request(
                    '/access/auth-token/domains/%s/relations'
                ))
                    ->setJwt((string)$jwt)
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
                        ]
                    )
            );
    }
}
