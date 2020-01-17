<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Authentication\TrustedApp;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Rule\JsonRule;
use hunomina\Validator\Json\Schema\JsonSchema;
use Jalismrs\Stalactite\Client\Authentication\Client as ParentClient;
use Jalismrs\Stalactite\Client\Authentication\Model\ModelFactory;
use Jalismrs\Stalactite\Client\Authentication\Model\TrustedAppModel;
use Jalismrs\Stalactite\Client\Authentication\Schema;
use Jalismrs\Stalactite\Client\ClientAbstract;
use Jalismrs\Stalactite\Client\ClientException;
use Jalismrs\Stalactite\Client\Response;
use function array_map;
use function array_merge;
use function vsprintf;

/**
 * Client
 *
 * @package Jalismrs\Stalactite\Client\Authentication\TrustedAppModel
 */
class Client extends
    ClientAbstract
{
    public const API_URL_PART = ParentClient::API_URL_PART . '/trustedApps';
    
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
        
        $response = $this->requestGet(
            vsprintf(
                '%s%s',
                [
                    $this->host,
                    self::API_URL_PART,
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
                'trustedApps' => array_map(
                    static function($trustedApp) {
                        return ModelFactory::createTrustedAppModel($trustedApp);
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
    public function get(string $uid, string $jwt) : Response
    {
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
        
        $response = $this->requestGet(
            vsprintf(
                '%s%s/%s',
                [
                    $this->host,
                    self::API_URL_PART,
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
                'trustedApp' => ModelFactory::createTrustedAppModel($response['trustedApp'])
            ]
        );
    }
    
    /**
     * update
     *
     * @param \Jalismrs\Stalactite\Client\Authentication\Model\TrustedAppModel $trustedAppModel
     * @param string                                                           $jwt
     *
     * @return \Jalismrs\Stalactite\Client\Response
     *
     * @throws \Jalismrs\Stalactite\Client\ClientException
     * @throws \hunomina\Validator\Json\Exception\InvalidDataTypeException
     * @throws \hunomina\Validator\Json\Exception\InvalidSchemaException
     */
    public function update(
        TrustedAppModel $trustedAppModel,
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
        
        $response = $this->requestPut(
            vsprintf(
                '%s%s/%s',
                [
                    $this->host,
                    self::API_URL_PART,
                    $trustedAppModel->getUid(),
                ],
            ),
            [
                'headers' => [
                    'X-API-TOKEN' => $jwt
                ],
                'json'    => [
                    'googleOAuthClientId' => $trustedAppModel->getGoogleOAuthClientId(),
                    'name'                => $trustedAppModel->getName(),
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
     * create
     *
     * @param \Jalismrs\Stalactite\Client\Authentication\Model\TrustedAppModel $trustedAppModel
     * @param string                                                           $jwt
     *
     * @return \Jalismrs\Stalactite\Client\Response
     *
     * @throws \Jalismrs\Stalactite\Client\ClientException
     * @throws \hunomina\Validator\Json\Exception\InvalidDataTypeException
     * @throws \hunomina\Validator\Json\Exception\InvalidSchemaException
     */
    public function create(
        TrustedAppModel $trustedAppModel,
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
        
        $response = $this->requestPost(
            vsprintf(
                '%s%s',
                [
                    $this->host,
                    self::API_URL_PART,
                ],
            ),
            [
                'headers' => [
                    'X-API-TOKEN' => $jwt
                ],
                'json'    => [
                    'googleOAuthClientId' => $trustedAppModel->getGoogleOAuthClientId(),
                    'name'                => $trustedAppModel->getName(),
                ]
            ],
            $schema
        );
        
        return new Response(
            $response['success'],
            $response['error'],
            [
                'trustedApp' => ModelFactory::createTrustedAppModel($response['trustedApp'])
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
    public function delete(
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
        
        $response = $this->requestDelete(
            vsprintf(
                '%s%s/%s',
                [
                    $this->host,
                    self::API_URL_PART,
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
     * @param \Jalismrs\Stalactite\Client\Authentication\Model\TrustedAppModel $trustedAppModel
     * @param string                                                           $jwt
     *
     * @return \Jalismrs\Stalactite\Client\Response
     *
     * @throws \Jalismrs\Stalactite\Client\ClientException
     * @throws \hunomina\Validator\Json\Exception\InvalidDataTypeException
     * @throws \hunomina\Validator\Json\Exception\InvalidSchemaException
     */
    public function resetAuthToken(
        TrustedAppModel $trustedAppModel,
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
        
        $response = $this->requestPut(
            vsprintf(
                '%s%s/%s/authToken/reset',
                [
                    $this->host,
                    self::API_URL_PART,
                    $trustedAppModel->getUid(),
                ],
            ),
            [
                'headers' => [
                    'X-API-TOKEN' => $jwt
                ],
                'json'    => [
                    'resetToken' => $trustedAppModel->getResetToken(),
                ]
            ],
            $schema
        );
        
        return new Response(
            $response['success'],
            $response['error'],
            [
                'trustedApp' => ModelFactory::createTrustedAppModel($response['trustedApp'])
            ]
        );
    }
}
