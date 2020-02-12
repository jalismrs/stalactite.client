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
use Jalismrs\Stalactite\Client\Exception\RequestConfigurationException;
use Jalismrs\Stalactite\Client\RequestConfiguration;
use Jalismrs\Stalactite\Client\Response;
use Jalismrs\Stalactite\Client\Util\SerializerException;
use function array_map;
use function assert;

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
     *
     * @throws RequestConfigurationException
     */
    public function __construct(
        Client $client
    ) {
        parent::__construct(
            $client
        );
        
        $this->requestConfigurations = [
            'getAccessClearance' => (new RequestConfiguration(
                '/access/users/%s/access/%s'
            ))
                ->setResponse(
                    static function(array $response) : array {
                        return [
                            'clearance' => ModelFactory::createAccessClearance($response['clearance']),
                        ];
                    }
                )
                ->setValidation(
                    [
                        'clearance' => [
                            'type'   => JsonRule::OBJECT_TYPE,
                            'schema' => Schema::ACCESS_CLEARANCE,
                        ],
                    ]
                ),
            'getRelations'       => (new RequestConfiguration(
                '/access/users/%s/relations'
            ))
                ->setValidation(
                    [
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
                    ]
                ),
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
     *
     * @throws RequestConfigurationException
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
     * @throws RequestConfigurationException
     * @throws SerializerException
     */
    public function getRelations(
        User $userModel,
        string $jwt
    ) : Response {
        $requestConfiguration = $this->requestConfigurations['getRelations'];
        assert($requestConfiguration instanceof RequestConfiguration);
        
        $requestConfiguration->setResponse(
            static function(array $response) use ($userModel) : array {
                return [
                    'relations' => array_map(
                        static function(array $relation) use ($userModel): DomainUserRelation {
                            $domainUserRelationModel = ModelFactory::createDomainUserRelation($relation);
                            $domainUserRelationModel->setUser($userModel);
                            
                            return $domainUserRelationModel;
                        },
                        $response['relations']
                    ),
                ];
            }
        );
        
        return $this
            ->getClient()
            ->request(
                $requestConfiguration,
                [
                    $userModel->getUid(),
                ],
                [
                    'headers' => [
                        'X-API-TOKEN' => $jwt
                    ]
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
     * @throws SerializerException
     */
    public function getAccessClearance(
        User $userModel,
        Domain $domainModel,
        string $jwt
    ) : Response {
        return $this
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
    }
}
