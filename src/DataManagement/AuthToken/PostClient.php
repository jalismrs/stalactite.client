<?php
declare(strict_types = 1);

namespace jalismrs\Stalactite\Client\DataManagement\AuthToken;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Rule\JsonRule;
use hunomina\Validator\Json\Schema\JsonSchema;
use jalismrs\Stalactite\Client\AbstractClient;
use jalismrs\Stalactite\Client\ClientException;
use jalismrs\Stalactite\Client\DataManagement\Model\ModelFactory;
use jalismrs\Stalactite\Client\DataManagement\Schema;
use jalismrs\Stalactite\Client\Response;

class PostClient extends
    AbstractClient
{
    public const API_URL_PREFIX = AuthTokenClient::API_URL_PREFIX . '/posts';
    
    /**
     * @param string $apiAuthToken
     *
     * @return Response
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function getAll(string $apiAuthToken) : Response
    {
        $jwt = AuthTokenClient::generateJwt($apiAuthToken, $this->userAgent);
        
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
            $this->apiHost . self::API_URL_PREFIX,
            [
                'headers' => ['X-API-TOKEN' => (string)$jwt]
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
     * @param string $uid
     * @param string $apiAuthToken
     *
     * @return Response
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function get(string $uid, string $apiAuthToken) : Response
    {
        $jwt = AuthTokenClient::generateJwt($apiAuthToken, $this->userAgent);
        
        $schema = new JsonSchema();
        $schema->setSchema(
            [
                'success' => ['type' => JsonRule::BOOLEAN_TYPE],
                'error'   => [
                    'type' => JsonRule::STRING_TYPE,
                    'null' => true
                ],
                'post'    => [
                    'type'   => JsonRule::OBJECT_TYPE,
                    'null'   => true,
                    'schema' => Schema::POST
                ]
            ]
        );
    
        $r = $this->requestGet(
            $this->apiHost . self::API_URL_PREFIX . '/' . $uid,
            [
                'headers' => ['X-API-TOKEN' => (string)$jwt]
            ],
            $schema
        );
        
        return (new Response())
            ->setSuccess($r['success'])
            ->setError($r['error'])
            ->setData(
                [
                    'post' => $r['post']
                        ? ModelFactory::createPost($r['post'])
                        : null
                ]
            );
    }
    
    /**
     * @param string $uid
     * @param string $apiAuthToken
     *
     * @return Response
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function getUsers(string $uid, string $apiAuthToken) : Response
    {
        $jwt = AuthTokenClient::generateJwt($apiAuthToken, $this->userAgent);
        
        $schema = new JsonSchema();
        $schema->setSchema(
            [
                'success' => ['type' => JsonRule::BOOLEAN_TYPE],
                'error'   => [
                    'type' => JsonRule::STRING_TYPE,
                    'null' => true
                ],
                'users'   => [
                    'type'   => JsonRule::LIST_TYPE,
                    'schema' => Schema::USER
                ]
            ]
        );
    
        $r = $this->requestGet(
            $this->apiHost . self::API_URL_PREFIX . '/' . $uid . '/users',
            [
                'headers' => ['X-API-TOKEN' => (string)$jwt]
            ],
            $schema
        );
        
        $users = [];
        foreach ($r['users'] as $user) {
            $users[] = ModelFactory::createUser($user);
        }
        
        return (new Response())
            ->setSuccess($r['success'])
            ->setError($r['error'])
            ->setData(
                [
                    'users' => $users
                ]
            );
    }
}
