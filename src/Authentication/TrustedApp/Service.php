<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Authentication\TrustedApp;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Rule\JsonRule;
use hunomina\Validator\Json\Schema\JsonSchema;
use Jalismrs\Stalactite\Client\AbstractService;
use Jalismrs\Stalactite\Client\Authentication\Model\ModelFactory;
use Jalismrs\Stalactite\Client\Authentication\Model\TrustedApp;
use Jalismrs\Stalactite\Client\Authentication\Schema;
use Jalismrs\Stalactite\Client\ClientException;
use Jalismrs\Stalactite\Client\Response;
use Jalismrs\Stalactite\Client\Util\SerializerException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use function array_map;
use function array_merge;
use function vsprintf;

/**
 * Service
 *
 * @package Jalismrs\Stalactite\Service\Authentication\TrustedApp
 */
class Service extends
    AbstractService
{
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
     */
    public function getAllTrustedApps(
        string $jwt
    ) : Response {
        $schema = new JsonSchema();
        $schema->setSchema(
            [
                'success'     => [
                    'type' => JsonRule::BOOLEAN_TYPE
                ],
                'error'       => [
                    'type' => JsonRule::STRING_TYPE,
                    'null' => true
                ],
                'trustedApps' => [
                    'type'   => JsonRule::LIST_TYPE,
                    'schema' => Schema::TRUSTED_APP
                ]
            ]
        );
        
        $response = $this
            ->getClient()
            ->get(
                '/auth/trustedApps',
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
     * @param string $uid
     * @param string $jwt
     *
     * @return Response
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function getTrustedApp(
        string $uid,
        string $jwt
    ) : Response {
        $schema = new JsonSchema();
        $schema->setSchema(
            [
                'success'    => [
                    'type' => JsonRule::BOOLEAN_TYPE
                ],
                'error'      => [
                    'type' => JsonRule::STRING_TYPE,
                    'null' => true
                ],
                'trustedApp' => [
                    'type'   => JsonRule::OBJECT_TYPE,
                    'schema' => Schema::TRUSTED_APP,
                    'null'   => true
                ]
            ]
        );
        
        $response = $this
            ->getClient()
            ->get(
                vsprintf(
                    '/auth/trustedApps/%s',
                    [
                        $uid,
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
        
        $response = $this
            ->getClient()
            ->put(
                vsprintf(
                    '/auth/trustedApps/%s',
                    [
                        $trustedAppModel->getUid(),
                    ],
                ),
                [
                    'headers' => [
                        'X-API-TOKEN' => $jwt
                    ],
                    'json'    => $this
                        ->getClient()
                        ->getSerializer()
                        ->normalize(
                            $trustedAppModel,
                            [
                                AbstractNormalizer::GROUPS => [
                                    'update',
                                ],
                            ]
                        ),
                ],
                $schema
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
     * @noinspection PhpUnusedLocalVariableInspection
     * @noinspection PhpUnusedLocalVariableInspection
     */
    public function createTrustedApp(
        TrustedApp $trustedAppModel,
        string $jwt
    ) : Response {
        $schema = new JsonSchema();
        $schema->setSchema(
            [
                'success'    => [
                    'type' => JsonRule::BOOLEAN_TYPE
                ],
                'error'      => [
                    'type' => JsonRule::STRING_TYPE,
                    'null' => true
                ],
                'trustedApp' => [
                    'type'   => JsonRule::OBJECT_TYPE,
                    'null'   => true,
                    'schema' => array_merge(
                        Schema::TRUSTED_APP,
                        [
                            'resetToken' => [
                                'type' => JsonRule::STRING_TYPE
                            ]
                        ]
                    )
                ]
            ]
        );
        
        $response = $this
            ->getClient()
            ->post(
                '/auth/trustedApps',
                [
                    'headers' => [
                        'X-API-TOKEN' => $jwt
                    ],
                    'json'    => $this
                        ->getClient()
                        ->getSerializer()
                        ->normalize(
                            $trustedAppModel,
                            [
                                AbstractNormalizer::GROUPS => [
                                    'create',
                                ],
                            ]
                        ),
                ],
                $schema
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
     * @param string $uid
     * @param string $resetToken
     * @param string $jwt
     *
     * @return Response
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function deleteTrustedApp(
        string $uid,
        string $resetToken,
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
                ]
            ]
        );
        
        $response = $this
            ->getClient()
            ->delete(
                vsprintf(
                    '/auth/trustedApps/%s',
                    [
                        $uid,
                    ],
                ),
                [
                    'headers' => [
                        'X-API-TOKEN' => $jwt
                    ],
                    'json'    => [
                        'resetToken' => $resetToken,
                    ]
                ],
                $schema
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
        $schema = new JsonSchema();
        $schema->setSchema(
            [
                'success'    => [
                    'type' => JsonRule::BOOLEAN_TYPE
                ],
                'error'      => [
                    'type' => JsonRule::STRING_TYPE,
                    'null' => true
                ],
                'trustedApp' => [
                    'type'   => JsonRule::OBJECT_TYPE,
                    'null'   => true,
                    'schema' => Schema::TRUSTED_APP
                ]
            ]
        );
        
        $response = $this
            ->getClient()
            ->put(
                vsprintf(
                    '/auth/trustedApps/%s/authToken/reset',
                    [
                        $trustedAppModel->getUid(),
                    ],
                ),
                [
                    'headers' => [
                        'X-API-TOKEN' => $jwt
                    ],
                    'json'    => $this
                        ->getClient()
                        ->getSerializer()
                        ->normalize(
                            $trustedAppModel,
                            [
                                AbstractNormalizer::GROUPS => [
                                    'reset',
                                ],
                            ]
                        )
                ],
                $schema
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
