<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Authentication\TrustedApp;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Rule\JsonRule;
use Jalismrs\Stalactite\Client\AbstractService;
use Jalismrs\Stalactite\Client\Authentication\Model\ModelFactory;
use Jalismrs\Stalactite\Client\Authentication\Model\TrustedApp;
use Jalismrs\Stalactite\Client\Authentication\Schema;
use Jalismrs\Stalactite\Client\Client;
use Jalismrs\Stalactite\Client\ClientException;
use Jalismrs\Stalactite\Client\Response;
use Jalismrs\Stalactite\Client\Util\SerializerException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use function array_map;
use function array_merge;

/**
 * Service
 *
 * @package Jalismrs\Stalactite\Service\Authentication\TrustedApp
 */
class Service extends
    AbstractService
{
    /**
     * Service constructor.
     *
     * @param Client $client
     */
    public function __construct(
        Client $client
    ) {
        parent::__construct(
            $client
        );
        
        $this->requestConfigurations = [
            'create'         => [
                'endpoint'      => '/auth/trustedApps',
                'method'        => 'POST',
                'validation'    => [
                    'trustedApp' => [
                        'type'   => JsonRule::OBJECT_TYPE,
                        'null'   => true,
                        'schema' => array_merge(
                            Schema::TRUSTED_APP,
                            [
                                'resetToken' => [
                                    'type' => JsonRule::STRING_TYPE
                                ],
                            ]
                        ),
                    ],
                ],
                'normalization' => [
                    AbstractNormalizer::GROUPS => [
                        'create',
                    ],
                ],
            ],
            'delete'         => [
                'endpoint' => '/auth/trustedApps/%s',
                'method'   => 'DELETE',
            ],
            'getAll'         => [
                'endpoint'   => '/auth/trustedApps',
                'method'     => 'GET',
                'validation' => [
                    'trustedApps' => [
                        'type'   => JsonRule::LIST_TYPE,
                        'schema' => Schema::TRUSTED_APP,
                    ],
                ],
            ],
            'get'            => [
                'endpoint'   => '/auth/trustedApps/%s',
                'method'     => 'GET',
                'validation' => [
                    'trustedApp' => [
                        'type'   => JsonRule::OBJECT_TYPE,
                        'schema' => Schema::TRUSTED_APP,
                        'null'   => true,
                    ],
                ],
            ],
            'resetAuthToken' => [
                'endpoint'      => '/auth/trustedApps/%s/authToken/reset',
                'method'        => 'PUT',
                'validation'    => [
                    'success'    => [
                        'type' => JsonRule::BOOLEAN_TYPE,
                    ],
                    'error'      => [
                        'type' => JsonRule::STRING_TYPE,
                        'null' => true,
                    ],
                    'trustedApp' => [
                        'type'   => JsonRule::OBJECT_TYPE,
                        'null'   => true,
                        'schema' => Schema::TRUSTED_APP,
                    ],
                ],
                'normalization' => [
                    AbstractNormalizer::GROUPS => [
                        'reset',
                    ],
                ],
            ],
            'update'         => [
                'endpoint'      => '/auth/trustedApps/%s',
                'method'        => 'PUT',
                'normalization' => [
                    AbstractNormalizer::GROUPS => [
                        'update',
                    ],
                ],
            ],
        ];
    }
    
    /**
     * getAllTrustedApps
     *
     * @param string $jwt
     *
     * @return Response
     *
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     * @throws SerializerException
     */
    public function getAllTrustedApps(
        string $jwt
    ) : Response {
        $response = $this
            ->getClient()
            ->request(
                $this->requestConfigurations['getAll'],
                [],
                [
                    'headers' => [
                        'X-API-TOKEN' => $jwt
                    ]
                ]
            );
        
        return new Response(
            $response['success'],
            $response['error'],
            [
                'trustedApps' => array_map(
                    static function($trustedApp) {
                        return ModelFactory::createTrustedApp($trustedApp);
                    },
                    $response['trustedApps']
                )
            ]
        );
    }
    
    /**
     * getTrustedApp
     *
     * @param string $uid
     * @param string $jwt
     *
     * @return Response
     *
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     * @throws SerializerException
     */
    public function getTrustedApp(
        string $uid,
        string $jwt
    ) : Response {
        $response = $this
            ->getClient()
            ->request(
                $this->requestConfigurations['get'],
                [
                    $uid,
                ],
                [
                    'headers' => [
                        'X-API-TOKEN' => $jwt
                    ]
                ]
            );
        
        return new Response(
            $response['success'],
            $response['error'],
            [
                'trustedApp' => ModelFactory::createTrustedApp($response['trustedApp'])
            ]
        );
    }
    
    /**
     * updateTrustedApp
     *
     * @param TrustedApp $trustedAppModel
     * @param string     $jwt
     *
     * @return Response
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     * @throws SerializerException
     */
    public function updateTrustedApp(
        TrustedApp $trustedAppModel,
        string $jwt
    ) : Response {
        $response = $this
            ->getClient()
            ->request(
                $this->requestConfigurations['update'],
                [
                    $trustedAppModel->getUid(),
                ],
                [
                    'headers' => [
                        'X-API-TOKEN' => $jwt
                    ],
                    'json'    => $trustedAppModel,
                ]
            );
        
        return (new Response(
            $response['success'],
            $response['error']
        ));
    }
    
    /**
     * createTrustedApp
     *
     * @param TrustedApp $trustedAppModel
     * @param string     $jwt
     *
     * @return Response
     *
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     * @throws SerializerException
     */
    public function createTrustedApp(
        TrustedApp $trustedAppModel,
        string $jwt
    ) : Response {
        $response = $this
            ->getClient()
            ->request(
                $this->requestConfigurations['create'],
                [],
                [
                    'headers' => [
                        'X-API-TOKEN' => $jwt
                    ],
                    'json'    => $trustedAppModel,
                ]
            );
        
        return new Response(
            $response['success'],
            $response['error'],
            [
                'trustedApp' => ModelFactory::createTrustedApp($response['trustedApp'])
            ]
        );
    }
    
    /**
     * deleteTrustedApp
     *
     * @param string $uid
     * @param string $resetToken
     * @param string $jwt
     *
     * @return Response
     *
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     * @throws SerializerException
     */
    public function deleteTrustedApp(
        string $uid,
        string $resetToken,
        string $jwt
    ) : Response {
        $response = $this
            ->getClient()
            ->request(
                $this->requestConfigurations['delete'],
                [
                    $uid,
                ],
                [
                    'headers' => [
                        'X-API-TOKEN' => $jwt
                    ],
                    'json'    => [
                        'resetToken' => $resetToken,
                    ]
                ]
            );
        
        return (new Response(
            $response['success'],
            $response['error']
        ));
    }
    
    /**
     * resetAuthToken
     *
     * @param TrustedApp $trustedAppModel
     * @param string     $jwt
     *
     * @return Response
     *
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     * @throws SerializerException
     */
    public function resetAuthToken(
        TrustedApp $trustedAppModel,
        string $jwt
    ) : Response {
        $response = $this
            ->getClient()
            ->request(
                $this->requestConfigurations['resetAuthToken'],
                [
                    $trustedAppModel->getUid(),
                ],
                [
                    'headers' => [
                        'X-API-TOKEN' => $jwt
                    ],
                    'json'    => $trustedAppModel
                ]
            );
        
        return new Response(
            $response['success'],
            $response['error'],
            [
                'trustedApp' => ModelFactory::createTrustedApp($response['trustedApp'])
            ]
        );
    }
}
