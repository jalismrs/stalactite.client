<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\DataManagement\User;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Rule\JsonRule;
use hunomina\Validator\Json\Schema\JsonSchema;
use Jalismrs\Stalactite\Client\ClientAbstract;
use Jalismrs\Stalactite\Client\ClientException;
use Jalismrs\Stalactite\Client\DataManagement\Model\ModelFactory;
use Jalismrs\Stalactite\Client\DataManagement\Model\UserModel;
use Jalismrs\Stalactite\Client\DataManagement\Schema;
use Jalismrs\Stalactite\Client\Response;
use Jalismrs\Stalactite\Client\DataManagement\Client as ParentClient;

/**
 * Client
 *
 * @package Jalismrs\Stalactite\Client\DataManagement\UserModel
 */
class Client extends
    ClientAbstract
{
    public const API_URL_PART = ParentClient::API_URL_PART . '/users';
    
    /*
     * -------------------------------------------------------------------------
     * Clients -----------------------------------------------------------------
     * -------------------------------------------------------------------------
     */
    /**
     * clientCertificationGraduation
     *
     * @return \Jalismrs\Stalactite\Client\DataManagement\User\CertificationGraduation\Client
     */
    public function clientCertificationGraduation() : CertificationGraduation\Client
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
     * clientLead
     *
     * @return \Jalismrs\Stalactite\Client\DataManagement\User\Lead\Client
     */
    public function clientLead() : Lead\Client
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
     * clientMe
     *
     * @return \Jalismrs\Stalactite\Client\DataManagement\User\Me\Client
     */
    public function clientMe() : Me\Client
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
     * clientPhoneLine
     *
     * @return \Jalismrs\Stalactite\Client\DataManagement\User\PhoneLine\Client
     */
    public function clientPhoneLine() : PhoneLine\Client
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
     * clientPost
     *
     * @return \Jalismrs\Stalactite\Client\DataManagement\User\Post\Client
     */
    public function clientPost() : Post\Client
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
    
    /*
     * -------------------------------------------------------------------------
     * API ---------------------------------------------------------------------
     * -------------------------------------------------------------------------
     */
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
    
        $response = $this->requestGet(
            $this->host . self::API_URL_PART,
            [
                'headers' => [
                    'X-API-TOKEN' => $jwt
                ]
            ],
            $schema
        );
        
        $users = [];
        foreach ($response['users'] as $user) {
            $users[] = ModelFactory::createUser($user);
        }
        
        return new Response(
            $response['success'],
            $response['error'],
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
    
        $response = $this->requestGet(
            $this->host . self::API_URL_PART . '/' . $uid,
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
                'user' => $response['user']
                    ? ModelFactory::createUser($response['user'])
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
    
        $response = $this->requestGet(
            $this->host . self::API_URL_PART,
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
            $response['success'],
            $response['error'],
            [
                'user' => $response['user']
                    ? ModelFactory::createUser($response['user'])
                    : null
            ]
        );
    }
    
    /**
     * @param UserModel $user
     * @param string    $jwt
     *
     * @return Response
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function create(UserModel $user, string $jwt) : Response
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
    
        $response = $this->requestPost(
            $this->host . self::API_URL_PART,
            [
                'headers' => [
                    'X-API-TOKEN' => $jwt
                ],
                'json'    => $body
            ],
            $schema
        );
        
        return new Response(
            $response['success'],
            $response['error'],
            [
                'user' => $response['user']
                    ? ModelFactory::createUser($response['user'])
                    : null
            ]
        );
    }
    
    /**
     * @param UserModel $user
     * @param string    $jwt
     *
     * @return Response
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function update(UserModel $user, string $jwt) : Response
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
    
        $response = $this->requestPut(
            $this->host . self::API_URL_PART . '/' . $user->getUid(),
            [
                'headers' => [
                    'X-API-TOKEN' => $jwt
                ],
                'json'    => $body
            ],
            $schema
        );
        
        return (new Response(
            $response['success'],
            $response['error']
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
    
        $response = $this->requestDelete(
            $this->host . self::API_URL_PART . '/' . $uid,
            [
                'headers' => [
                    'X-API-TOKEN' => $jwt
                ]
            ],
            $schema
        );
        
        return (new Response(
            $response['success'],
            $response['error']
        ));
    }
}
