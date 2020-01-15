<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\DataManagement\User;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Rule\JsonRule;
use hunomina\Validator\Json\Schema\JsonSchema;
use Jalismrs\Stalactite\Client\AbstractClient;
use Jalismrs\Stalactite\Client\ClientException;
use Jalismrs\Stalactite\Client\DataManagement\Model\ModelFactory;
use Jalismrs\Stalactite\Client\DataManagement\Model\User;
use Jalismrs\Stalactite\Client\DataManagement\Schema;
use Jalismrs\Stalactite\Client\Response;

/**
 * Client
 *
 * @package Jalismrs\Stalactite\Client\DataManagement\User
 */
class Client extends
    AbstractClient
{
    public const API_URL_PREFIX = \Jalismrs\Stalactite\Client\DataManagement\Client::API_URL_PREFIX . '/users';
    
    /**
     * me
     *
     * @return \Jalismrs\Stalactite\Client\DataManagement\User\Me\Client
     */
    public function me() : Me\Client
    {
        static $client = null;
        
        if (null === $client) {
            $client = new Me\Client(
                $this->host,
                $this->userAgent,
                $this->httpClient
            );
        }
        
        return $client;
    }
    
    /**
     * post
     *
     * @return \Jalismrs\Stalactite\Client\DataManagement\User\Post\Client
     */
    public function post() : Post\Client
    {
        static $client = null;
        
        if (null === $client) {
            $client = new Post\Client(
                $this->host,
                $this->userAgent,
                $this->httpClient
            );
        }
        
        return $client;
    }
    
    /**
     * lead
     *
     * @return \Jalismrs\Stalactite\Client\DataManagement\User\Lead\Client
     */
    public function lead() : Lead\Client
    {
        static $client = null;
        
        if (null === $client) {
            $client = new Lead\Client(
                $this->host,
                $this->userAgent,
                $this->httpClient
            );
        }
        
        return $client;
    }
    
    /**
     * certificationGraduation
     *
     * @return \Jalismrs\Stalactite\Client\DataManagement\User\CertificationGraduation\Client
     */
    public function certificationGraduation() : CertificationGraduation\Client
    {
        static $client = null;
        
        if (null === $client) {
            $client = new CertificationGraduation\Client(
                $this->host,
                $this->userAgent,
                $this->httpClient
            );
        }
        
        return $client;
    }
    
    /**
     * phoneLine
     *
     * @return \Jalismrs\Stalactite\Client\DataManagement\User\PhoneLine\Client
     */
    public function phoneLine() : PhoneLine\Client
    {
        static $client = null;
        
        if (null === $client) {
            $client = new PhoneLine\Client(
                $this->host,
                $this->userAgent,
                $this->httpClient
            );
        }
        
        return $client;
    }
    
    /**
     * @param string $jwt
     *
     * @return Response
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function getAll(string $jwt) : Response
    {
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
                'users'   => [
                    'type'   => JsonRule::LIST_TYPE,
                    'schema' => Schema::MINIMAL_USER
                ]
            ]
        );
        
        $r = $this->requestGet(
            $this->host . self::API_URL_PREFIX,
            [
                'headers' => [
                    'X-API-TOKEN' => $jwt
                ]
            ],
            $schema
        );
        
        $users = [];
        foreach ($r['users'] as $user) {
            $users[] = ModelFactory::createUser($user);
        }
        
        return new Response(
            $r['success'],
            $r['error'],
            [
                'users' => $users
            ]
        );
    }
    
    /**
     * @param string $uid
     * @param string $jwt
     *
     * @return Response
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function get(string $uid, string $jwt) : Response
    {
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
                'user'    => [
                    'type'   => JsonRule::OBJECT_TYPE,
                    'null'   => true,
                    'schema' => Schema::USER
                ]
            ]
        );
        
        $r = $this->requestGet(
            $this->host . self::API_URL_PREFIX . '/' . $uid,
            [
                'headers' => [
                    'X-API-TOKEN' => $jwt
                ]
            ],
            $schema
        );
        
        return new Response(
            $r['success'],
            $r['error'],
            [
                'user' => $r['user']
                    ? ModelFactory::createUser($r['user'])
                    : null
            ]
        );
    }
    
    /**
     * @param string $email
     * @param string $googleId
     * @param string $jwt
     *
     * @return Response
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function getByEmailAndGoogleId(string $email, string $googleId, string $jwt) : Response
    {
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
                'user'    => [
                    'type'   => JsonRule::OBJECT_TYPE,
                    'null'   => true,
                    'schema' => Schema::USER
                ]
            ]
        );
        
        $r = $this->requestGet(
            $this->host . self::API_URL_PREFIX,
            [
                'headers' => [
                    'X-API-TOKEN' => $jwt
                ],
                'query'   => [
                    'email'    => $email,
                    'googleId' => $googleId
                ]
            ],
            $schema
        );
        
        return new Response(
            $r['success'],
            $r['error'],
            [
                'user' => $r['user']
                    ? ModelFactory::createUser($r['user'])
                    : null
            ]
        );
    }
    
    /**
     * @param User   $user
     * @param string $jwt
     *
     * @return Response
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function create(User $user, string $jwt) : Response
    {
        $body = $user->asMinimalArray();
        
        foreach ($user->getPosts() as $post) {
            $body['posts'] = $post->getUid();
        }
        
        foreach ($user->getLeads() as $lead) {
            $body['leads'] = $lead->getUid();
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
                ],
                'user'    => [
                    'type'   => JsonRule::OBJECT_TYPE,
                    'null'   => true,
                    'schema' => Schema::USER
                ]
            ]
        );
        
        $r = $this->requestPost(
            $this->host . self::API_URL_PREFIX,
            [
                'headers' => [
                    'X-API-TOKEN' => $jwt
                ],
                'json'    => $body
            ],
            $schema
        );
        
        return new Response(
            $r['success'],
            $r['error'],
            [
                'user' => $r['user']
                    ? ModelFactory::createUser($r['user'])
                    : null
            ]
        );
    }
    
    /**
     * @param User   $user
     * @param string $jwt
     *
     * @return Response
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function update(User $user, string $jwt) : Response
    {
        $body = $user->asMinimalArray();
        unset($body['googleId'], $body['uid']);
        
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
        
        $r = $this->requestPut(
            $this->host . self::API_URL_PREFIX . '/' . $user->getUid(),
            [
                'headers' => [
                    'X-API-TOKEN' => $jwt
                ],
                'json'    => $body
            ],
            $schema
        );
        
        return (new Response(
            $r['success'],
            $r['error']
        ));
    }
    
    /**
     * @param string $uid
     * @param string $jwt
     *
     * @return Response
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function delete(string $uid, string $jwt) : Response
    {
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
        
        $r = $this->requestDelete(
            $this->host . self::API_URL_PREFIX . '/' . $uid,
            [
                'headers' => [
                    'X-API-TOKEN' => $jwt
                ]
            ],
            $schema
        );
        
        return (new Response(
            $r['success'],
            $r['error']
        ));
    }
}
