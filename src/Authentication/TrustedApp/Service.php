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
use Jalismrs\Stalactite\Client\RequestConfiguration;
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
            'create'         => (new RequestConfiguration(
                '/auth/trustedApps'
            ))
                ->setMethod('POST')
                ->setNormalization(
                    [
                        AbstractNormalizer::GROUPS => [
                            'create',
                        ],
                    ]
                )
                ->setResponse(
                    static function(array $response) : array {
                        return [
                            'trustedApp' => ModelFactory::createTrustedApp($response['trustedApp']),
                        ];
                    }
                )
                ->setValidation(
                    [
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
                    ]
                ),
            'delete'         => (new RequestConfiguration(
                '/auth/trustedApps/%s'
            ))
                ->setMethod('DELETE'),
            'getAll'         => (new RequestConfiguration(
                '/auth/trustedApps'
            ))
                ->setResponse(
                    static function(array $response) : array {
                        return [
                            'trustedApps' => array_map(
                                static function($trustedApp) {
                                    return ModelFactory::createTrustedApp($trustedApp);
                                },
                                $response['trustedApps']
                            ),
                        ];
                    }
                )
                ->setValidation(
                    [
                        'trustedApps' => [
                            'type'   => JsonRule::LIST_TYPE,
                            'schema' => Schema::TRUSTED_APP,
                        ],
                    ]
                ),
            'get'            => (new RequestConfiguration(
                '/auth/trustedApps/%s'
            ))
                ->setResponse(
                    static function(array $response) : array {
                        return [
                            'trustedApp' => ModelFactory::createTrustedApp($response['trustedApp']),
                        ];
                    }
                )
                ->setValidation(
                    [
                        'trustedApp' => [
                            'type'   => JsonRule::OBJECT_TYPE,
                            'schema' => Schema::TRUSTED_APP,
                            'null'   => true,
                        ],
                    ]
                ),
            'resetAuthToken' => (new RequestConfiguration(
                '/auth/trustedApps/%s/authToken/reset'
            ))
                ->setMethod('PUT')
                ->setNormalization(
                    [
                        AbstractNormalizer::GROUPS => [
                            'reset',
                        ],
                    ]
                )
                ->setResponse(
                    static function(array $response) : array {
                        return [
                            'trustedApp' => ModelFactory::createTrustedApp($response['trustedApp']),
                        ];
                    }
                )
                ->setValidation(
                    [
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
                    ]
                ),
            'update'         => (new RequestConfiguration(
                '/auth/trustedApps/%s'
            ))
                ->setMethod('PUT')
                ->setNormalization(
                    [
                        AbstractNormalizer::GROUPS => [
                            'update',
                        ],
                    ]
                ),
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
        return $this
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
        return $this
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
        return $this
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
        return $this
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
        return $this
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
        return $this
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
    }
}
