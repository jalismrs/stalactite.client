<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\DataManagement\User\Post;

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
 * @package Jalismrs\Stalactite\Client\DataManagement\UserModel\PostModel
 */
class Client extends
    ClientAbstract
{
    public const API_URL_PART = '/posts';
    
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
                'posts'   => [
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
        
        return new Response(
            $response['success'],
            $response['error'],
            [
                'posts' => array_map(
                    static function($post) {
                        return ModelFactory::createPostModel($post);
                    },
                    $response['posts']
                )
            ]
        );
    }
    
    /**
     * addPosts
     *
     * @param \Jalismrs\Stalactite\Client\DataManagement\Model\UserModel $userModel
     * @param array                                                      $postModels
     * @param string                                                     $jwt
     *
     * @return \Jalismrs\Stalactite\Client\Response
     *
     * @throws \Jalismrs\Stalactite\Client\ClientException
     * @throws \hunomina\Validator\Json\Exception\InvalidDataTypeException
     * @throws \hunomina\Validator\Json\Exception\InvalidSchemaException
     */
    public function addPosts(
        UserModel $userModel,
        array $postModels,
        string $jwt
    ) : Response {
        $body = [
            'posts' => []
        ];
        
        foreach ($postModels as $postModel) {
            if (!$postModel instanceof PostModel) {
                throw new ClientException(
                    '$posts array parameter must be a PostModel model array',
                    ClientException::INVALID_PARAMETER_PASSED_TO_CLIENT
                );
            }
            
            if (null !== $postModel->getUid()) {
                $body['posts'][] = $postModel->getUid();
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
     * removePosts
     *
     * @param \Jalismrs\Stalactite\Client\DataManagement\Model\UserModel $userModel
     * @param array                                                      $postModels
     * @param string                                                     $jwt
     *
     * @return \Jalismrs\Stalactite\Client\Response
     *
     * @throws \Jalismrs\Stalactite\Client\ClientException
     * @throws \hunomina\Validator\Json\Exception\InvalidDataTypeException
     * @throws \hunomina\Validator\Json\Exception\InvalidSchemaException
     */
    public function removePosts(
        UserModel $userModel,
        array $postModels,
        string $jwt
    ) : Response {
        $body = [
            'posts' => []
        ];
        
        foreach ($postModels as $postModel) {
            if (!$postModel instanceof PostModel) {
                throw new ClientException(
                    '$posts array parameter must be a PostModel model array',
                    ClientException::INVALID_PARAMETER_PASSED_TO_CLIENT
                );
            }
            
            if (null !== $postModel->getUid()) {
                $body['posts'][] = $postModel->getUid();
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
