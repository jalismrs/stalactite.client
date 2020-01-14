<?php
declare(strict_types = 1);

namespace jalismrs\Stalactite\Client\DataManagement\User;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Rule\JsonRule;
use hunomina\Validator\Json\Schema\JsonSchema;
use jalismrs\Stalactite\Client\AbstractClient;
use jalismrs\Stalactite\Client\ClientException;
use jalismrs\Stalactite\Client\DataManagement\Model\ModelFactory;
use jalismrs\Stalactite\Client\DataManagement\Model\Post;
use jalismrs\Stalactite\Client\DataManagement\Model\User;
use jalismrs\Stalactite\Client\DataManagement\Schema;
use jalismrs\Stalactite\Client\Response;

class PostClient extends
    AbstractClient
{
    public const API_URL_PREFIX = '/posts';
    
    /**
     * @param User   $user
     * @param string $jwt
     *
     * @return Response
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function getAll(User $user, string $jwt) : Response
    {
        $schema = new JsonSchema();
        $schema->setSchema(
            [
                'success' => ['type' => JsonRule::BOOLEAN_TYPE],
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
    
        $r = $this->requestGet(
            $this->apiHost . UserClient::API_URL_PREFIX . '/' . $user->getUid() . self::API_URL_PREFIX,
            [
                'headers' => ['X-API-TOKEN' => $jwt]
            ],
            $schema
        );
        
        $posts = [];
        foreach ($r['posts'] as $post) {
            $posts[] = ModelFactory::createPost($post);
        }
        
        return (new Response())
            ->setSuccess($r['success'])
            ->setError($r['error'])
            ->setData(
                [
                    'posts' => $posts
                ]
            );
    }
    
    /**
     * @param User   $user
     * @param array  $posts
     * @param string $jwt
     *
     * @return Response
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function addPosts(User $user, array $posts, string $jwt) : Response
    {
        $body = ['posts' => []];
        
        foreach ($posts as $post) {
            if (!($post instanceof Post)) {
                throw new ClientException('$posts array parameter must be a Post model array', ClientException::INVALID_PARAMETER_PASSED_TO_CLIENT);
            }
            
            if ($post->getUid() !== null) {
                $body['posts'][] = $post->getUid();
            }
        }
        
        $schema = new JsonSchema();
        $schema->setSchema(
            [
                'success' => ['type' => JsonRule::BOOLEAN_TYPE],
                'error'   => [
                    'type' => JsonRule::STRING_TYPE,
                    'null' => true
                ]
            ]
        );
    
        $r = $this->requestPost(
            $this->apiHost . UserClient::API_URL_PREFIX . '/' . $user->getUid() . self::API_URL_PREFIX,
            [
                'headers' => ['X-API-TOKEN' => $jwt],
                'json'    => $body
            ],
            $schema
        );
        
        return (new Response())
            ->setSuccess($r['success'])
            ->setError($r['error']);
    }
    
    /**
     * @param User   $user
     * @param array  $posts
     * @param string $jwt
     *
     * @return Response
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function removePosts(User $user, array $posts, string $jwt) : Response
    {
        $body = ['posts' => []];
        
        foreach ($posts as $post) {
            if (!($post instanceof Post)) {
                throw new ClientException('$posts array parameter must be a Post model array', ClientException::INVALID_PARAMETER_PASSED_TO_CLIENT);
            }
            
            if ($post->getUid() !== null) {
                $body['posts'][] = $post->getUid();
            }
        }
        
        $schema = new JsonSchema();
        $schema->setSchema(
            [
                'success' => ['type' => JsonRule::BOOLEAN_TYPE],
                'error'   => [
                    'type' => JsonRule::STRING_TYPE,
                    'null' => true
                ]
            ]
        );
    
        $r = $this->requestDelete(
            $this->apiHost . UserClient::API_URL_PREFIX . '/' . $user->getUid() . self::API_URL_PREFIX,
            [
                'headers' => ['X-API-TOKEN' => $jwt],
                'json'    => $body
            ],
            $schema
        );
        
        return (new Response())
            ->setSuccess($r['success'])
            ->setError($r['error']);
    }
}
