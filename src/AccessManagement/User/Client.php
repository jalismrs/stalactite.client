<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\AccessManagement\User;

use hunomina\Validator\Json\Rule\JsonRule;
use hunomina\Validator\Json\Schema\JsonSchema;
use Jalismrs\Stalactite\Client\AccessManagement\Client as ParentClient;
use Jalismrs\Stalactite\Client\AccessManagement\Model\DomainUserRelationModel;
use Jalismrs\Stalactite\Client\AccessManagement\Model\ModelFactory;
use Jalismrs\Stalactite\Client\AccessManagement\Schema;
use Jalismrs\Stalactite\Client\ClientAbstract;
use Jalismrs\Stalactite\Client\DataManagement\Model\DomainModel;
use Jalismrs\Stalactite\Client\DataManagement\Model\UserModel;
use Jalismrs\Stalactite\Client\DataManagement\Schema as DataManagementSchema;
use Jalismrs\Stalactite\Client\Response;
use function array_map;
use function vsprintf;

/**
 * Client
 *
 * @package Jalismrs\Stalactite\Client\AccessManagement\UserModel
 */
class Client extends
    ClientAbstract
{
    public const API_URL_PART = ParentClient::API_URL_PART . '/users';
    
    private $clientMe;
    /*
     * -------------------------------------------------------------------------
     * Clients -----------------------------------------------------------------
     * -------------------------------------------------------------------------
     */
    /**
     * getClientMe
     *
     * @return \Jalismrs\Stalactite\Client\AccessManagement\User\Me\Client
     *
     * @throws \InvalidArgumentException
     */
    public function getClientMe() : Me\Client
    {
        if (null === $this->clientMe) {
            $this->clientMe = new Me\Client(
                $this->host,
                $this->userAgent,
                $this->httpClient
            );
        }
        
        return $this->clientMe;
    }
    
    /*
     * -------------------------------------------------------------------------
     * API ---------------------------------------------------------------------
     * -------------------------------------------------------------------------
     */
    /**
     * getRelations
     *
     * @param \Jalismrs\Stalactite\Client\DataManagement\Model\UserModel $userModel
     * @param string                                                     $jwt
     *
     * @return \Jalismrs\Stalactite\Client\Response
     *
     * @throws \Jalismrs\Stalactite\Client\ClientException
     * @throws \hunomina\Validator\Json\Exception\InvalidDataTypeException
     * @throws \hunomina\Validator\Json\Exception\InvalidSchemaException
     */
    public function getRelations(
        UserModel $userModel,
        string $jwt
    ) : Response {
        $schema = new JsonSchema();
        $schema->setSchema(
            [
                'success'   => [
                    'type' => JsonRule::BOOLEAN_TYPE
                ],
                'error'     => [
                    'type' => JsonRule::STRING_TYPE,
                    'null' => true
                ],
                'relations' => [
                    'type'   => JsonRule::LIST_TYPE,
                    'schema' => [
                        'uid'    => [
                            'type' => JsonRule::STRING_TYPE
                        ],
                        'domain' => [
                            'type'   => JsonRule::OBJECT_TYPE,
                            'schema' => DataManagementSchema::DOMAIN
                        ]
                    ]
                ]
            ]
        );
        
        $response = $this->requestGet(
            vsprintf(
                '%s%s/%s/relations',
                [
                    $this->host,
                    self::API_URL_PART,
                    $userModel->getUid(),
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
                'relations' => array_map(
                    static function(array $relation) use ($userModel): DomainUserRelationModel {
                        $domainUserRelationModel = ModelFactory::createDomainUserRelationModel($relation);
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
     * @param \Jalismrs\Stalactite\Client\DataManagement\Model\UserModel   $userModel
     * @param \Jalismrs\Stalactite\Client\DataManagement\Model\DomainModel $domainModel
     * @param string                                                       $jwt
     *
     * @return \Jalismrs\Stalactite\Client\Response
     *
     * @throws \Jalismrs\Stalactite\Client\ClientException
     * @throws \hunomina\Validator\Json\Exception\InvalidDataTypeException
     * @throws \hunomina\Validator\Json\Exception\InvalidSchemaException
     */
    public function getAccessClearance(
        UserModel $userModel,
        DomainModel $domainModel,
        string $jwt
    ) : Response {
        $schema = new JsonSchema();
        $schema->setSchema(
            [
                'success'   => [
                    'type' => JsonRule::BOOLEAN_TYPE
                ],
                'error'     => [
                    'type' => JsonRule::STRING_TYPE,
                    'null' => true
                ],
                'clearance' => [
                    'type'   => JsonRule::OBJECT_TYPE,
                    'schema' => Schema::ACCESS_CLEARANCE
                ]
            ]
        );
        
        $response = $this->requestGet(
            vsprintf(
                '%s%s/%s/access/%s',
                [
                    $this->host,
                    self::API_URL_PART,
                    $userModel->getUid(),
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
                'clearance' => ModelFactory::createAccessClearanceModel($response['clearance'])
            ]
        );
    }
}
