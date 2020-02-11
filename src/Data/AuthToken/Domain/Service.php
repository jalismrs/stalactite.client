<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Data\AuthToken\Domain;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Rule\JsonRule;
use hunomina\Validator\Json\Schema\JsonSchema;
use Jalismrs\Stalactite\Client\AbstractService;
use Jalismrs\Stalactite\Client\ClientException;
use Jalismrs\Stalactite\Client\Data\AuthToken\JwtFactory;
use Jalismrs\Stalactite\Client\Data\Model\ModelFactory;
use Jalismrs\Stalactite\Client\Data\Schema;
use Jalismrs\Stalactite\Client\Response;
use function array_map;
use function vsprintf;

/**
 * Service
 *
 * @package Jalismrs\Stalactite\Service\Data\AuthToken\Domain
 */
class Service extends
    AbstractService
{
    private const REQUEST_GET_ALL_CONFIGURATION                 = [
        'endpoint' => '/data/auth-token/domains',
        'method'   => 'GET',
        'schema'   => [
            'domains' => [
                'type'   => JsonRule::LIST_TYPE,
                'schema' => Schema::DOMAIN
            ]
        ],
    ];
    private const REQUEST_GET_BY_NAME_AND_API_KEY_CONFIGURATION = [
        'endpoint' => '/data/auth-token/domains',
        'method'   => 'GET',
        'schema'   => [
            'domains' => [
                'type'   => JsonRule::LIST_TYPE,
                'schema' => Schema::DOMAIN
            ]
        ],
    ];
    private const REQUEST_GET_BY_NAME_CONFIGURATION             = [
        'endpoint' => '/data/auth-token/domains',
        'method'   => 'GET',
        'schema'   => [
            'domains' => [
                'type'   => JsonRule::LIST_TYPE,
                'schema' => Schema::DOMAIN
            ]
        ],
    ];
    private const REQUEST_GET_CONFIGURATION                     = [
        'endpoint' => '/data/auth-token/domains/%s',
        'method'   => 'GET',
        'schema'   => [
            'domain'  => [
                'type'   => JsonRule::OBJECT_TYPE,
                'null'   => true,
                'schema' => Schema::DOMAIN
            ]
        ],
    ];
    
    /**
     * getAllDomains
     *
     * @param string $apiAuthToken
     *
     * @return Response
     *
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function getAllDomains(
        string $apiAuthToken
    ) : Response {
        $jwt = JwtFactory::generateJwt(
            $apiAuthToken,
            $this
                ->getClient()
                ->getUserAgent()
        );
        
        $response = $this
            ->getClient()
            ->request(
                self::REQUEST_GET_ALL_CONFIGURATION,
                [],
                [
                    'headers' => [
                        'X-API-TOKEN' => (string)$jwt
                    ]
                ]
            );
        
        return new Response(
            $response['success'],
            $response['error'],
            [
                'domains' => array_map(
                    static function($domain) {
                        return ModelFactory::createDomain($domain);
                    },
                    $response['domains']
                )
            ]
        );
    }
    
    /**
     * @param string $name
     * @param string $apiKey
     * @param string $apiAuthToken
     *
     * @return Response
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function getByNameAndApiKey(
        string $name,
        string $apiKey,
        string $apiAuthToken
    ) : Response {
        $jwt = JwtFactory::generateJwt(
            $apiAuthToken,
            $this
                ->getClient()
                ->getUserAgent()
        );
        
        $response = $this
            ->getClient()
            ->request(
                self::REQUEST_GET_BY_NAME_AND_API_KEY_CONFIGURATION,
                [],
                [
                    'headers' => [
                        'X-API-TOKEN' => (string)$jwt
                    ],
                    'query'   => [
                        'name'   => $name,
                        'apiKey' => $apiKey
                    ]
                ]
            );
        
        return new Response(
            $response['success'],
            $response['error'],
            [
                'domains' => array_map(
                    static function($domain) {
                        return ModelFactory::createDomain($domain);
                    },
                    $response['domains']
                )
            ]
        );
    }
    
    /**
     * @param string $name
     * @param string $apiAuthToken
     *
     * @return Response
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function getByName(
        string $name,
        string $apiAuthToken
    ) : Response {
        $jwt = JwtFactory::generateJwt(
            $apiAuthToken,
            $this
                ->getClient()
                ->getUserAgent()
        );
        
        $response = $this
            ->getClient()
            ->request(
                self::REQUEST_GET_BY_NAME_CONFIGURATION,
                [],
                [
                    'headers' => [
                        'X-API-TOKEN' => (string)$jwt
                    ],
                    'query'   => [
                        'name' => $name
                    ]
                ]
            );
        
        return new Response(
            $response['success'],
            $response['error'],
            [
                'domains' => array_map(
                    static function($domain) {
                        return ModelFactory::createDomain($domain);
                    },
                    $response['domains']
                )
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
    public function getDomain(
        string $uid,
        string $apiAuthToken
    ) : Response {
        $jwt = JwtFactory::generateJwt(
            $apiAuthToken,
            $this
                ->getClient()
                ->getUserAgent()
        );
        
        $response = $this
            ->getClient()
            ->request(
                self::REQUEST_GET_CONFIGURATION,
                [
                    $uid,
                ],
                [
                    'headers' => [
                        'X-API-TOKEN' => (string)$jwt
                    ]
                ]
            );
        
        return new Response(
            $response['success'],
            $response['error'],
            [
                'domain' => null === $response['domain']
                    ? null
                    : ModelFactory::createDomain($response['domain']),
            ]
        );
    }
}
