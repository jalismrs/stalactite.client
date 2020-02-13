<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Authentication\TrustedApp;

use hunomina\Validator\Json\Rule\JsonRule;
use Jalismrs\Stalactite\Client\AbstractService;
use Jalismrs\Stalactite\Client\Authentication\Model\ModelFactory;
use Jalismrs\Stalactite\Client\Authentication\Model\TrustedApp;
use Jalismrs\Stalactite\Client\Authentication\Schema;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\RequestException;
use Jalismrs\Stalactite\Client\Exception\SerializerException;
use Jalismrs\Stalactite\Client\Exception\ValidatorException;
use Jalismrs\Stalactite\Client\Util\Request;
use Jalismrs\Stalactite\Client\Util\Response;
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
     * @throws RequestException
     * @throws SerializerException
     * @throws ValidatorException
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
                    ->setJwt($jwt)
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
     * @throws RequestException
     * @throws SerializerException
     * @throws ValidatorException
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
                    ->setJwt($jwt)
                    ->setResponse(
                        static function(array $response) : array {
                            return [
                                'trustedApp' => ModelFactory::createTrustedApp($response['trustedApp']),
                            ];
                        }
                    )
                    ->setUriParameters(
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
     *
     * @throws ClientException
     * @throws RequestException
     * @throws SerializerException
     * @throws ValidatorException
     */
    public function updateTrustedApp(
        TrustedApp $trustedAppModel,
        string $jwt
    ) : Response {
        return $this
            ->getClient()
            ->request(
                (new Request(
                    '/auth/trustedApps/%s',
                    'PUT'
                ))
                    ->setJson($trustedAppModel)
                    ->setJwt($jwt)
                    ->setNormalization(
                        [
                            AbstractNormalizer::GROUPS => [
                                'update',
                            ],
                        ]
                    )
                    ->setUriParameters(
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
     * @throws RequestException
     * @throws SerializerException
     * @throws ValidatorException
     */
    public function createTrustedApp(
        TrustedApp $trustedAppModel,
        string $jwt
    ) : Response {
        return $this
            ->getClient()
            ->request(
                (new Request(
                    '/auth/trustedApps',
                    'POST'
                ))
                    ->setJson($trustedAppModel)
                    ->setJwt($jwt)
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
     * @throws RequestException
     * @throws SerializerException
     * @throws ValidatorException
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
                    '/auth/trustedApps/%s',
                    'DELETE'
                ))
                    ->setJson(
                        [
                            'resetToken' => $resetToken,
                        ]
                    )
                    ->setJwt($jwt)
                    ->setUriParameters(
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
     * @throws RequestException
     * @throws SerializerException
     * @throws ValidatorException
     */
    public function resetAuthToken(
        TrustedApp $trustedAppModel,
        string $jwt
    ) : Response {
        return $this
            ->getClient()
            ->request(
                (new Request(
                    '/auth/trustedApps/%s/authToken/reset',
                    'PUT'
                ))
                    ->setJson($trustedAppModel)
                    ->setJwt($jwt)
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
                    ->setUriParameters(
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
