<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\DataManagement\User\Lead;

use hunomina\Validator\Json\Rule\JsonRule;
use hunomina\Validator\Json\Schema\JsonSchema;
use Jalismrs\Stalactite\Client\ClientAbstract;
use Jalismrs\Stalactite\Client\ClientException;
use Jalismrs\Stalactite\Client\DataManagement\Model\ModelFactory;
use Jalismrs\Stalactite\Client\DataManagement\Model\PostModel;
use Jalismrs\Stalactite\Client\DataManagement\Model\UserModel;
use Jalismrs\Stalactite\Client\DataManagement\Schema;
use Jalismrs\Stalactite\Client\DataManagement\User\Client as ParentClient;
use Jalismrs\Stalactite\Client\Response;
use function array_map;
use function vsprintf;

/**
 * Client
 *
 * @package Jalismrs\Stalactite\Client\DataManagement\UserModel\Lead
 */
class Client extends
    ClientAbstract
{
    public const API_URL_PART = '/leads';
    
    /**
     * getAll
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
    public function getAll(
        UserModel $userModel,
        string $jwt
    ) : Response {
        $schema = new JsonSchema();
        $schema->setSchema(
            [
                'success' => [
                    'type' => JsonRule::BOOLEAN_TYPE
                ],
                'error'   => [
                    'type' => JsonRule::STRING_TYPE,
                    'null' => true
                ],
                'leads'   => [
                    'type'   => JsonRule::LIST_TYPE,
                    'schema' => Schema::POST
                ]
            ]
        );
        
        $response = $this->requestGet(
            vsprintf(
                '%s%s/%s%s',
                [
                    $this->host,
                    ParentClient::API_URL_PART,
                    $userModel->getUid(),
                    self::API_URL_PART,
                ],
            ),
            [
                'headers' => [
                    'X-API-TOKEN' => $jwt
                ]
            ],
            $schema
        );
        
        // TODO: vérifier pouruqoi createLead n'est pas utilisé
        
        return new Response(
            $response['success'],
            $response['error'],
            [
                'leads' => array_map(
                    static function($lead) {
                        return ModelFactory::createPostModel($lead);
                    },
                    $response['leads']
                )
            ]
        );
    }
    
    /**
     * addLeads
     *
     * @param \Jalismrs\Stalactite\Client\DataManagement\Model\UserModel $userModel
     * @param array                                                      $leadModels
     * @param string                                                     $jwt
     *
     * @return \Jalismrs\Stalactite\Client\Response
     *
     * @throws \Jalismrs\Stalactite\Client\ClientException
     * @throws \hunomina\Validator\Json\Exception\InvalidDataTypeException
     * @throws \hunomina\Validator\Json\Exception\InvalidSchemaException
     */
    public function addLeads(
        UserModel $userModel,
        array $leadModels,
        string $jwt
    ) : Response {
        $body = [
            'leads' => []
        ];
        
        foreach ($leadModels as $leadModel) {
            if (!$leadModel instanceof PostModel) {
                throw new ClientException(
                    '$leads array parameter must be a PostModel model array',
                    ClientException::INVALID_PARAMETER_PASSED_TO_CLIENT
                );
            }
            
            if (null !== $leadModel->getUid()) {
                $body['leads'][] = $leadModel->getUid();
            }
        }
        
        $schema = new JsonSchema();
        $schema->setSchema(
            [
                'success' => [
                    'type' => JsonRule::BOOLEAN_TYPE
                ],
                'error'   => [
                    'type' => JsonRule::STRING_TYPE,
                    'null' => true
                ]
            ]
        );
        
        $response = $this->requestPost(
            vsprintf(
                '%s%s/%s%s',
                [
                    $this->host,
                    ParentClient::API_URL_PART,
                    $userModel->getUid(),
                    self::API_URL_PART,
                ],
            ),
            [
                'headers' => [
                    'X-API-TOKEN' => $jwt
                ],
                'json'    => $body,
            ],
            $schema
        );
        
        return (new Response(
            $response['success'],
            $response['error']
        ));
    }
    
    /**
     * removeLeads
     *
     * @param \Jalismrs\Stalactite\Client\DataManagement\Model\UserModel $userModel
     * @param array                                                      $leadModels
     * @param string                                                     $jwt
     *
     * @return \Jalismrs\Stalactite\Client\Response
     *
     * @throws \Jalismrs\Stalactite\Client\ClientException
     * @throws \hunomina\Validator\Json\Exception\InvalidDataTypeException
     * @throws \hunomina\Validator\Json\Exception\InvalidSchemaException
     */
    public function removeLeads(
        UserModel $userModel,
        array $leadModels,
        string $jwt
    ) : Response {
        $body = [
            'leads' => []
        ];
        
        foreach ($leadModels as $leadModel) {
            if (!$leadModel instanceof PostModel) {
                throw new ClientException(
                    '$leads array parameter must be a PostModel model array',
                    ClientException::INVALID_PARAMETER_PASSED_TO_CLIENT
                );
            }
            
            if (null !== $leadModel->getUid()) {
                $body['posts'][] = $leadModel->getUid();
            }
        }
        
        $schema = new JsonSchema();
        $schema->setSchema(
            [
                'success' => [
                    'type' => JsonRule::BOOLEAN_TYPE
                ],
                'error'   => [
                    'type' => JsonRule::STRING_TYPE,
                    'null' => true
                ]
            ]
        );
        
        $response = $this->requestDelete(
            vsprintf(
                '%s%s/%s%s',
                [
                    $this->host,
                    ParentClient::API_URL_PART,
                    $userModel->getUid(),
                    self::API_URL_PART,
                ],
            ),
            [
                'headers' => [
                    'X-API-TOKEN' => $jwt
                ],
                'json'    => $body,
            ],
            $schema
        );
        
        return (new Response(
            $response['success'],
            $response['error']
        ));
    }
}
