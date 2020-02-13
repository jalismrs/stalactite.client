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
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\SerializerException;
use Jalismrs\Stalactite\Client\Util\Response;
use Jalismrs\Stalactite\Client\Util\Request;
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
                (new Request(
                    '/auth/trustedApps'
                ))
                    ->setOptions(
                        [
                            'headers' => [
                                'X-API-TOKEN' => $jwt
                            ]
                        ]
                    )
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
                    )
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
                (new Request(
                    '/auth/trustedApps/%s'
                ))
                    ->setOptions(
                        [
                            'headers' => [
                                'X-API-TOKEN' => $jwt
                            ]
                        ]
                    )
                    ->setResponse(
                        static function(array $response) : array {
                            return [
                                'trustedApp' => ModelFactory::createTrustedApp($response['trustedApp']),
                            ];
                        }
                    )
                    ->setUriDatas(
                        [
                            $uid,
                        ]
                    )
                    ->setValidation(
                        [
                            'trustedApp' => [
                                'type'   => JsonRule::OBJECT_TYPE,
                                'schema' => Schema::TRUSTED_APP,
                                'null'   => true,
                            ],
                        ]
                    )
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
                (new Request(
                    '/auth/trustedApps/%s'
                ))
                    ->setMethod('PUT')
                    ->setNormalization(
                        [
                            AbstractNormalizer::GROUPS => [
                                'update',
                            ],
                        ]
                    )
                    ->setOptions(
                        [
                            'headers' => [
                                'X-API-TOKEN' => $jwt
                            ],
                            'json'    => $trustedAppModel,
                        ]
                    )
                    ->setUriDatas(
                        [
                            $trustedAppModel->getUid(),
                        ]
                    )
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
                (new Request(
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
                    ->setOptions(
                        [
                            'headers' => [
                                'X-API-TOKEN' => $jwt
                            ],
                            'json'    => $trustedAppModel,
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
                    )
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
                (new Request(
                    '/auth/trustedApps/%s'
                ))
                    ->setMethod('DELETE')
                    ->setOptions(
                        [
                            'headers' => [
                                'X-API-TOKEN' => $jwt
                            ],
                            'json'    => [
                                'resetToken' => $resetToken,
                            ]
                        ]
                    )
                    ->setUriDatas(
                        [
                            $uid,
                        ]
                    )
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
                (new Request(
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
                    ->setOptions(
                        [
                            'headers' => [
                                'X-API-TOKEN' => $jwt
                            ],
                            'json'    => $trustedAppModel
                        ]
                    )
                    ->setResponse(
                        static function(array $response) : array {
                            return [
                                'trustedApp' => ModelFactory::createTrustedApp($response['trustedApp']),
                            ];
                        }
                    )
                    ->setUriDatas(
                        [
                            $trustedAppModel->getUid(),
                        ]
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
                    )
            );
    }
}
