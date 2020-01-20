<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Data\User\Lead;

use hunomina\Validator\Json\Rule\JsonRule;
use hunomina\Validator\Json\Schema\JsonSchema;
use Jalismrs\Stalactite\Client\ClientAbstract;
use Jalismrs\Stalactite\Client\ClientException;
use Jalismrs\Stalactite\Client\Data\Model\ModelFactory;
use Jalismrs\Stalactite\Client\Data\Model\PostModel;
use Jalismrs\Stalactite\Client\Data\Model\UserModel;
use Jalismrs\Stalactite\Client\Data\Schema;
use Jalismrs\Stalactite\Client\Response;
use function array_map;
use function vsprintf;

/**
 * Client
 *
 * @package Jalismrs\Stalactite\Client\Data\UserModel\Lead
 */
class Client extends
    ClientAbstract
{
    /**
     * getAll
     *
     * @param \Jalismrs\Stalactite\Client\Data\Model\UserModel $userModel
     * @param string                                           $jwt
     *
     * @return \Jalismrs\Stalactite\Client\Response
     *
     * @throws \Jalismrs\Stalactite\Client\ClientException
     * @throws \hunomina\Validator\Json\Exception\InvalidDataTypeException
     * @throws \hunomina\Validator\Json\Exception\InvalidSchemaException
     */
    public function getAllLeads(
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
        
        $response = $this->get(
            vsprintf(
                '%s/data/users/%s/leads',
                [
                    $this->host,
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
     * @param \Jalismrs\Stalactite\Client\Data\Model\UserModel $userModel
     * @param array                                            $leadModels
     * @param string                                           $jwt
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
        
        $response = $this->post(
            vsprintf(
                '%s/data/users/%s/leads',
                [
                    $this->host,
                    $userModel->getUid(),
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
     * @param \Jalismrs\Stalactite\Client\Data\Model\UserModel $userModel
     * @param array                                            $leadModels
     * @param string                                           $jwt
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
        
        $response = $this->delete(
            vsprintf(
                '%s/data/users/%s/leads',
                [
                    $this->host,
                    $userModel->getUid(),
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
