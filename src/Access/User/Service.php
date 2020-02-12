<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Access\User;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Rule\JsonRule;
use Jalismrs\Stalactite\Client\AbstractService;
use Jalismrs\Stalactite\Client\Access\Model\DomainUserRelation;
use Jalismrs\Stalactite\Client\Access\Model\ModelFactory;
use Jalismrs\Stalactite\Client\Access\Schema;
use Jalismrs\Stalactite\Client\Client;
use Jalismrs\Stalactite\Client\ClientException;
use Jalismrs\Stalactite\Client\Data\Model\Domain;
use Jalismrs\Stalactite\Client\Data\Model\User;
use Jalismrs\Stalactite\Client\Data\Schema as DataSchema;
use Jalismrs\Stalactite\Client\Response;
use function array_map;

/**
 * Service
 *
 * @package Jalismrs\Stalactite\Service\Access\User
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
            'getAccessClearance' => [
                'endpoint' => '/access/users/%s/access/%s',
                'method'   => 'GET',
                'schema'   => [
                    'clearance' => [
                        'type'   => JsonRule::OBJECT_TYPE,
                        'schema' => Schema::ACCESS_CLEARANCE,
                    ],
                ],
            ],
            'getRelations'       => [
                'endpoint' => '/access/users/%s/relations',
                'method'   => 'GET',
                'schema'   => [
                    'relations' => [
                        'type'   => JsonRule::LIST_TYPE,
                        'schema' => [
                            'uid'    => [
                                'type' => JsonRule::STRING_TYPE,
                            ],
                            'domain' => [
                                'type'   => JsonRule::OBJECT_TYPE,
                                'schema' => DataSchema::DOMAIN,
                            ],
                        ],
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
     * getRelations
     *
     * @param User   $userModel
     * @param string $jwt
     *
     * @return Response
     *
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function getRelations(
        User $userModel,
        string $jwt
    ) : Response {
        $response = $this
            ->getClient()
            ->request(
                $this->requestConfigurations['getRelations'],
                [
                    $userModel->getUid(),
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
                'relations' => array_map(
                    static function(array $relation) use ($userModel): DomainUserRelation {
                        $domainUserRelationModel = ModelFactory::createDomainUserRelation($relation);
                        $domainUserRelationModel->setUser($userModel);
                        
                        return $domainUserRelationModel;
                    },
                    $response['relations']
                )
            ]
        );
    }
    
    /**
     * getAccessClearance
     *
     * @param User   $userModel
     * @param Domain $domainModel
     * @param string $jwt
     *
     * @return Response
     *
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function getAccessClearance(
        User $userModel,
        Domain $domainModel,
        string $jwt
    ) : Response {
        $response = $this
            ->getClient()
            ->request(
                $this->requestConfigurations['getAccessClearance'],
                [
                    $userModel->getUid(),
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
                'clearance' => ModelFactory::createAccessClearance($response['clearance'])
            ]
        );
    }
}
