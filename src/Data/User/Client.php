<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Data\User;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Rule\JsonRule;
use hunomina\Validator\Json\Schema\JsonSchema;
use Jalismrs\Stalactite\Client\ClientAbstract;
use Jalismrs\Stalactite\Client\ClientException;
use Jalismrs\Stalactite\Client\Data\Model\ModelFactory;
use Jalismrs\Stalactite\Client\Data\Model\PostModel;
use Jalismrs\Stalactite\Client\Data\Model\UserModel;
use Jalismrs\Stalactite\Client\Data\Schema;
use Jalismrs\Stalactite\Client\Response;
use function array_map;
use function array_merge;
use function vsprintf;

/**
 * Client
 *
 * @package Jalismrs\Stalactite\Client\Data\UserModel
 */
class Client extends
    ClientAbstract
{
    private $clientCertificationGraduation;
    private $clientLead;
    private $clientMe;
    private $clientPhoneLine;
    private $clientPost;
    /*
     * -------------------------------------------------------------------------
     * Clients -----------------------------------------------------------------
     * -------------------------------------------------------------------------
     */
    /**
     * certificationGraduation
     *
     * @return CertificationGraduation\Client
     */
    public function certificationGraduations(): CertificationGraduation\Client
    {
        if (null === $this->clientCertificationGraduation) {
            $this->clientCertificationGraduation = new CertificationGraduation\Client(
                $this->host,
                $this->userAgent,
                $this->httpClient
            );
        }

        return $this->clientCertificationGraduation;
    }

    /**
     * lead
     *
     * @return Lead\Client
     */
    public function leads(): Lead\Client
    {
        if (null === $this->clientLead) {
            $this->clientLead = new Lead\Client(
                $this->host,
                $this->userAgent,
                $this->httpClient
            );
        }

        return $this->clientLead;
    }

    /**
     * me
     *
     * @return Me\Client
     */
    public function me(): Me\Client
    {
        if (null === $this->clientMe) {
            $this->clientMe = new Me\Client(
                $this->host,
                $this->userAgent,
                $this->httpClient
            );
        }

        return $this->clientMe;
    }

    /**
     * phoneLine
     *
     * @return PhoneLine\Client
     */
    public function phoneLines(): PhoneLine\Client
    {
        if (null === $this->clientPhoneLine) {
            $this->clientPhoneLine = new PhoneLine\Client(
                $this->host,
                $this->userAgent,
                $this->httpClient
            );
        }

        return $this->clientPhoneLine;
    }

    /**
     * post
     *
     * @return Post\Client
     */
    public function posts(): Post\Client
    {
        if (null === $this->clientPost) {
            $this->clientPost = new Post\Client(
                $this->host,
                $this->userAgent,
                $this->httpClient
            );
        }

        return $this->clientPost;
    }

    /*
     * -------------------------------------------------------------------------
     * API ---------------------------------------------------------------------
     * -------------------------------------------------------------------------
     */
    /**
     * getAllUsers
     *
     * @param string $jwt
     *
     * @return Response
     *
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function getAllUsers(
        string $jwt
    ): Response
    {
        $schema = new JsonSchema();
        $schema->setSchema(
            [
                'success' => [
                    'type' => JsonRule::BOOLEAN_TYPE
                ],
                'error' => [
                    'type' => JsonRule::STRING_TYPE,
                    'null' => true
                ],
                'users' => [
                    'type' => JsonRule::LIST_TYPE,
                    'schema' => Schema::MINIMAL_USER
                ]
            ]
        );

        $response = $this->get(
            vsprintf(
                '%s/data/users',
                [
                    $this->host,
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
                'users' => array_map(
                    static function ($user) {
                        return ModelFactory::createUserModel($user);
                    },
                    $response['users']
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
    public function getUser(
        string $uid,
        string $jwt
    ): Response
    {
        $schema = new JsonSchema();
        $schema->setSchema(
            [
                'success' => [
                    'type' => JsonRule::BOOLEAN_TYPE
                ],
                'error' => [
                    'type' => JsonRule::STRING_TYPE,
                    'null' => true
                ],
                'user' => [
                    'type' => JsonRule::OBJECT_TYPE,
                    'null' => true,
                    'schema' => Schema::USER
                ]
            ]
        );

        $response = $this->get(
            vsprintf(
                '%s/data/users/%s',
                [
                    $this->host,
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
                'user' => null === $response['user']
                    ? null
                    : ModelFactory::createUserModel($response['user']),
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
    public function getByEmailAndGoogleId(string $email, string $googleId, string $jwt): Response
    {
        $schema = new JsonSchema();
        $schema->setSchema(
            [
                'success' => [
                    'type' => JsonRule::BOOLEAN_TYPE
                ],
                'error' => [
                    'type' => JsonRule::STRING_TYPE,
                    'null' => true
                ],
                'user' => [
                    'type' => JsonRule::OBJECT_TYPE,
                    'null' => true,
                    'schema' => Schema::USER
                ]
            ]
        );

        $response = $this->get(
            vsprintf(
                '%s/data/users',
                [
                    $this->host,
                ],
            ),
            [
                'headers' => [
                    'X-API-TOKEN' => $jwt
                ],
                'query' => [
                    'email' => $email,
                    'googleId' => $googleId
                ]
            ],
            $schema
        );

        return new Response(
            $response['success'],
            $response['error'],
            [
                'user' => null === $response['user']
                    ? null
                    : ModelFactory::createUserModel($response['user']),
            ]
        );
    }

    /**
     * create
     *
     * @param UserModel $userModel
     * @param string $jwt
     *
     * @return Response
     *
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function createUser(
        UserModel $userModel,
        string $jwt
    ): Response
    {
        $schema = new JsonSchema();
        $schema->setSchema(
            [
                'success' => [
                    'type' => JsonRule::BOOLEAN_TYPE
                ],
                'error' => [
                    'type' => JsonRule::STRING_TYPE,
                    'null' => true
                ],
                'user' => [
                    'type' => JsonRule::OBJECT_TYPE,
                    'null' => true,
                    'schema' => Schema::USER
                ]
            ]
        );

        $response = $this->post(
            vsprintf(
                '%s/data/users',
                [
                    $this->host,
                ],
            ),
            [
                'headers' => [
                    'X-API-TOKEN' => $jwt
                ],
                'json' => array_merge(
                    $userModel->asMinimalArray(),
                    [
                        'leads' => array_map(
                            static function (PostModel $leadModel): ?string {
                                return $leadModel->getUid();
                            },
                            $userModel->getLeads()
                        ),
                        'posts' => array_map(
                            static function (PostModel $postModel): ?string {
                                return $postModel->getUid();
                            },
                            $userModel->getPosts()
                        ),
                    ],
                ),
            ],
            $schema
        );

        return new Response(
            $response['success'],
            $response['error'],
            [
                'user' => null === $response['user']
                    ? null
                    : ModelFactory::createUserModel($response['user']),
            ]
        );
    }

    /**
     * update
     *
     * @param UserModel $userModel
     * @param string $jwt
     *
     * @return Response
     *
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function updateUser(
        UserModel $userModel,
        string $jwt
    ): Response
    {
        $body = $userModel->asMinimalArray();
        unset($body['googleId'], $body['uid']);

        $schema = new JsonSchema();
        $schema->setSchema(
            [
                'success' => [
                    'type' => JsonRule::BOOLEAN_TYPE
                ],
                'error' => [
                    'type' => JsonRule::STRING_TYPE,
                    'null' => true
                ]
            ]
        );

        $response = $this->put(
            vsprintf(
                '%s/data/users/%s',
                [
                    $this->host,
                    $userModel->getUid(),
                ],
            ),
            [
                'headers' => [
                    'X-API-TOKEN' => $jwt
                ],
                'json' => $body,
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
    public function deleteUser(string $uid, string $jwt): Response
    {
        $schema = new JsonSchema();
        $schema->setSchema(
            [
                'success' => [
                    'type' => JsonRule::BOOLEAN_TYPE
                ],
                'error' => [
                    'type' => JsonRule::STRING_TYPE,
                    'null' => true
                ]
            ]
        );

        $response = $this->delete(
            vsprintf(
                '%s/data/users/%s',
                [
                    $this->host,
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

        return (new Response(
            $response['success'],
            $response['error']
        ));
    }
}
