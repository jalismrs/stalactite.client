<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\DataManagement;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Rule\JsonRule;
use hunomina\Validator\Json\Schema\JsonSchema;
use Jalismrs\Stalactite\Client\AbstractClient;
use Jalismrs\Stalactite\Client\ClientException;
use Jalismrs\Stalactite\Client\DataManagement\Model\Domain;
use Jalismrs\Stalactite\Client\DataManagement\Model\ModelFactory;
use Jalismrs\Stalactite\Client\Response;

/**
 * DomainClient
 *
 * @package Jalismrs\Stalactite\Client\DataManagement
 */
class DomainClient extends
    AbstractClient
{
    public const API_URL_PREFIX = Client::API_URL_PREFIX . '/domains';
    
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
                'domains' => [
                    'type'   => JsonRule::LIST_TYPE,
                    'schema' => Schema::DOMAIN
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
        
        $domains = [];
        foreach ($r['domains'] as $domain) {
            $domains[] = ModelFactory::createDomain($domain);
        }
        
        return new Response(
            $r['success'],
            $r['error'],
            [
                'domains' => $domains
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
                'domain'  => [
                    'type'   => JsonRule::OBJECT_TYPE,
                    'null'   => true,
                    'schema' => Schema::DOMAIN
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
                'domain' => $r['domain']
                    ? ModelFactory::createDomain($r['domain'])
                    : null
            ]
        );
    }
    
    /**
     * @param string $name
     * @param string $jwt
     *
     * @return Response
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function getByName(string $name, string $jwt) : Response
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
                'domains' => [
                    'type'   => JsonRule::LIST_TYPE,
                    'schema' => Schema::DOMAIN
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
                    'name' => $name
                ]
            ],
            $schema
        );
        
        $domains = [];
        foreach ($r['domains'] as $domain) {
            $domains[] = ModelFactory::createDomain($domain);
        }
        
        return new Response(
            $r['success'],
            $r['error'],
            [
                'domains' => $domains
            ]
        );
    }
    
    /**
     * @param string $name
     * @param string $apiKey
     * @param string $jwt
     *
     * @return Response
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function getByNameAndApiKey(string $name, string $apiKey, string $jwt) : Response
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
                'domains' => [
                    'type'   => JsonRule::LIST_TYPE,
                    'schema' => Schema::DOMAIN
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
                    'name'   => $name,
                    'apiKey' => $apiKey
                ]
            ],
            $schema
        );
        
        $domains = [];
        foreach ($r['domains'] as $domain) {
            $domains[] = ModelFactory::createDomain($domain);
        }
        
        return new Response(
            $r['success'],
            $r['error'],
            [
                'domains' => $domains
            ]
        );
    }
    
    /**
     * @param Domain $domain
     * @param string $jwt
     *
     * @return Response
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function create(Domain $domain, string $jwt) : Response
    {
        $body = [
            'name'           => $domain->getName(),
            'type'           => $domain->getType(),
            'externalAuth'   => $domain->hasExternalAuth(),
            'apiKey'         => $domain->getApiKey(),
            'generationDate' => $domain->getGenerationDate()
        ];
        
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
                'domain'  => [
                    'type'   => JsonRule::OBJECT_TYPE,
                    'null'   => true,
                    'schema' => Schema::DOMAIN
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
                'domain' => $r['domain']
                    ? ModelFactory::createDomain($r['domain'])
                    : null
            ]
        );
    }
    
    /**
     * @param Domain $domain
     * @param string $jwt
     *
     * @return Response
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function update(Domain $domain, string $jwt) : Response
    {
        $body = [
            'name'           => $domain->getName(),
            'type'           => $domain->getType(),
            'externalAuth'   => $domain->hasExternalAuth(),
            'apiKey'         => $domain->getApiKey(),
            'generationDate' => $domain->getGenerationDate()
        ];
        
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
            $this->host . self::API_URL_PREFIX . '/' . $domain->getUid(),
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
