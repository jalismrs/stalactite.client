<?php
declare(strict_types = 1);

namespace jalismrs\Stalactite\Client\DataManagement;

use hunomina\Validator\Json\Rule\JsonRule;
use hunomina\Validator\Json\Schema\JsonSchema;
use jalismrs\Stalactite\Client\AbstractClient;
use jalismrs\Stalactite\Client\DataManagement\Model\ModelFactory;
use jalismrs\Stalactite\Client\DataManagement\Model\Post;
use jalismrs\Stalactite\Client\Response;

/**
 * PostClient
 *
 * @package jalismrs\Stalactite\Client\DataManagement
 */
class PostClient extends
    AbstractClient
{
    public const API_URL_PREFIX = Client::API_URL_PREFIX . '/posts';
    
    /**
     * getAll
     *
     * @param string $jwt
     *
     * @return \jalismrs\Stalactite\Client\Response
     *
     * @throws \hunomina\Validator\Json\Exception\InvalidDataTypeException
     * @throws \hunomina\Validator\Json\Exception\InvalidSchemaException
     * @throws \jalismrs\Stalactite\Client\ClientException
     */
    public function getAll(string $jwt) : Response
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
        
        $r = $this->request(
            'GET',
            $this->apiHost . self::API_URL_PREFIX,
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
     * get
     *
     * @param string $uid
     * @param string $jwt
     *
     * @return \jalismrs\Stalactite\Client\Response
     *
     * @throws \hunomina\Validator\Json\Exception\InvalidDataTypeException
     * @throws \hunomina\Validator\Json\Exception\InvalidSchemaException
     * @throws \jalismrs\Stalactite\Client\ClientException
     */
    public function get(string $uid, string $jwt) : Response
    {
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
        
        $r = $this->request(
            'GET',
            $this->apiHost . self::API_URL_PREFIX . '/' . $uid,
            [
                'headers' => ['X-API-TOKEN' => $jwt]
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
     * create
     *
     * @param \jalismrs\Stalactite\Client\DataManagement\Model\Post $post
     * @param string                                                $jwt
     *
     * @return \jalismrs\Stalactite\Client\Response
     *
     * @throws \hunomina\Validator\Json\Exception\InvalidDataTypeException
     * @throws \hunomina\Validator\Json\Exception\InvalidSchemaException
     * @throws \jalismrs\Stalactite\Client\ClientException
     */
    public function create(Post $post, string $jwt) : Response
    {
        $body = [
            'name'      => $post->getName(),
            'shortName' => $post->getShortName(),
            'admin'     => $post->hasAdminAccess(),
            'access'    => $post->allowAccess()
        ];
        
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
        
        $r = $this->request(
            'POST',
            $this->apiHost . self::API_URL_PREFIX,
            [
                'headers' => ['X-API-TOKEN' => $jwt],
                'json'    => $body
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
     * update
     *
     * @param \jalismrs\Stalactite\Client\DataManagement\Model\Post $post
     * @param string                                                $jwt
     *
     * @return \jalismrs\Stalactite\Client\Response
     *
     * @throws \hunomina\Validator\Json\Exception\InvalidDataTypeException
     * @throws \hunomina\Validator\Json\Exception\InvalidSchemaException
     * @throws \jalismrs\Stalactite\Client\ClientException
     */
    public function update(Post $post, string $jwt) : Response
    {
        $body = [
            'name'      => $post->getName(),
            'shortName' => $post->getShortName(),
            'admin'     => $post->hasAdminAccess(),
            'access'    => $post->allowAccess()
        ];
        
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
        
        $r = $this->request(
            'PUT',
            $this->apiHost . self::API_URL_PREFIX . '/' . $post->getUid(),
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
     * delete
     *
     * @param string $uid
     * @param string $jwt
     *
     * @return \jalismrs\Stalactite\Client\Response
     *
     * @throws \hunomina\Validator\Json\Exception\InvalidDataTypeException
     * @throws \hunomina\Validator\Json\Exception\InvalidSchemaException
     * @throws \jalismrs\Stalactite\Client\ClientException
     */
    public function delete(string $uid, string $jwt) : Response
    {
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
        
        $r = $this->request(
            'DELETE',
            $this->apiHost . self::API_URL_PREFIX . '/' . $uid,
            [
                'headers' => ['X-API-TOKEN' => $jwt]
            ],
            $schema
        );
        
        return (new Response())
            ->setSuccess($r['success'])
            ->setError($r['error']);
    }
    
    /**
     * getUsers
     *
     * @param string $uid
     * @param string $jwt
     *
     * @return \jalismrs\Stalactite\Client\Response
     *
     * @throws \hunomina\Validator\Json\Exception\InvalidDataTypeException
     * @throws \hunomina\Validator\Json\Exception\InvalidSchemaException
     * @throws \jalismrs\Stalactite\Client\ClientException
     */
    public function getUsers(string $uid, string $jwt) : Response
    {
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
        
        $r = $this->request(
            'GET',
            $this->apiHost . self::API_URL_PREFIX . '/' . $uid . '/users',
            [
                'headers' => ['X-API-TOKEN' => $jwt]
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
