<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Data\User;

use hunomina\DataValidator\Rule\Json\JsonRule;
use InvalidArgumentException;
use Jalismrs\Stalactite\Client\AbstractService;
use Jalismrs\Stalactite\Client\Data\Model\ModelFactory;
use Jalismrs\Stalactite\Client\Data\Model\Post;
use Jalismrs\Stalactite\Client\Data\Model\User;
use Jalismrs\Stalactite\Client\Data\Schema;
use Jalismrs\Stalactite\Client\Data\User\Post\Service as PostService;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\RequestException;
use Jalismrs\Stalactite\Client\Exception\SerializerException;
use Jalismrs\Stalactite\Client\Exception\ValidatorException;
use Jalismrs\Stalactite\Client\Util\ModelHelper;
use Jalismrs\Stalactite\Client\Util\Request;
use Jalismrs\Stalactite\Client\Util\Response;
use Jalismrs\Stalactite\Client\Util\Serializer;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use function array_map;
use function array_merge;

/**
 * Service
 *
 * @package Jalismrs\Stalactite\Service\Data\User
 */
class Service extends
    AbstractService
{
    private ?Lead\Service $serviceLead = null;
    private ?Me\Service $serviceMe = null;
    private ?PostService $servicePost = null;

    /*
     * -------------------------------------------------------------------------
     * Clients -----------------------------------------------------------------
     * -------------------------------------------------------------------------
     */

    /**
     * leads
     *
     * @return Lead\Service
     */
    public function leads(): Lead\Service
    {
        if ($this->serviceLead === null) {
            $this->serviceLead = new Lead\Service($this->getClient());
        }

        return $this->serviceLead;
    }

    /**
     * me
     *
     * @return Me\Service
     */
    public function me(): Me\Service
    {
        if ($this->serviceMe === null) {
            $this->serviceMe = new Me\Service($this->getClient());
        }

        return $this->serviceMe;
    }

    /**
     * posts
     *
     * @return PostService
     */
    public function posts(): PostService
    {
        if ($this->servicePost === null) {
            $this->servicePost = new PostService($this->getClient());
        }

        return $this->servicePost;
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
     * @throws RequestException
     * @throws SerializerException
     * @throws ValidatorException
     */
    public function getAllUsers(
        string $jwt
    ): Response
    {
        return $this
            ->getClient()
            ->request(
                (new Request(
                    '/data/users'
                ))
                    ->setJwt($jwt)
                    ->setResponse(
                        static function (array $response): array {
                            return [
                                'users' => array_map(
                                    static function ($user) {
                                        return ModelFactory::createUser($user);
                                    },
                                    $response['users']
                                ),
                            ];
                        }
                    )
                    ->setValidation(
                        [
                            'users' => [
                                'type' => JsonRule::LIST_TYPE,
                                'schema' => Schema::USER,
                            ],
                        ]
                    )
            );
    }

    /**
     * getUser
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
    public function getUser(
        string $uid,
        string $jwt
    ): Response
    {
        return $this
            ->getClient()
            ->request(
                (new Request(
                    '/data/users/%s'
                ))
                    ->setJwt($jwt)
                    ->setResponse(
                        static function (array $response): array {
                            return [
                                'user' => $response['user'] === null
                                    ? null
                                    : ModelFactory::createUser($response['user']),
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
                            'user' => [
                                'type' => JsonRule::OBJECT_TYPE,
                                'null' => true,
                                'schema' => Schema::USER,
                            ],
                        ]
                    )
            );
    }

    /**
     * getByEmailAndGoogleId
     *
     * @param string $email
     * @param string $googleId
     * @param string $jwt
     *
     * @return Response
     *
     * @throws ClientException
     * @throws RequestException
     * @throws SerializerException
     * @throws ValidatorException
     */
    public function getByEmailAndGoogleId(
        string $email,
        string $googleId,
        string $jwt
    ): Response
    {
        return $this
            ->getClient()
            ->request(
                (new Request(
                    '/data/users'
                ))
                    ->setJwt($jwt)
                    ->setQueryParameters(
                        [
                            'email' => $email,
                            'googleId' => $googleId
                        ]
                    )
                    ->setResponse(
                        static function (array $response): array {
                            return [
                                'user' => $response['user'] === null
                                    ? null
                                    : ModelFactory::createUser($response['user']),
                            ];
                        }
                    )
                    ->setValidation(
                        [
                            'user' => [
                                'type' => JsonRule::OBJECT_TYPE,
                                'null' => true,
                                'schema' => Schema::USER,
                            ],
                        ]
                    )
            );
    }

    /**
     * createUser
     *
     * @param User $userModel
     * @param string $jwt
     *
     * @return Response
     *
     * @throws ClientException
     * @throws InvalidArgumentException
     * @throws RequestException
     * @throws SerializerException
     * @throws ValidatorException
     */
    public function createUser(
        User $userModel,
        string $jwt
    ): Response
    {
        return $this
            ->getClient()
            ->request(
                (new Request(
                    '/data/users',
                    'POST'
                ))
                    ->setJson(
                        array_merge(
                            Serializer::getInstance()
                                ->normalize(
                                    $userModel,
                                    [
                                        AbstractNormalizer::GROUPS => [
                                            'create',
                                        ],
                                    ]
                                ),
                            [
                                'leads' => ModelHelper::getUids(
                                    $userModel->getLeads(),
                                    Post::class
                                ),
                                'posts' => ModelHelper::getUids(
                                    $userModel->getPosts(),
                                    Post::class
                                ),
                            ],
                        )
                    )
                    ->setJwt($jwt)
                    ->setNormalization(
                        [
                            AbstractNormalizer::GROUPS => [
                                'create',
                            ],
                        ]
                    )
                    ->setResponse(
                        static function (array $response): array {
                            return [
                                'user' => $response['user'] === null
                                    ? null
                                    : ModelFactory::createUser($response['user']),
                            ];
                        }
                    )
                    ->setValidation(
                        [
                            'user' => [
                                'type' => JsonRule::OBJECT_TYPE,
                                'null' => true,
                                'schema' => Schema::USER,
                            ],
                        ]
                    )
            );
    }

    /**
     * updateUser
     *
     * @param User $userModel
     * @param string $jwt
     *
     * @return Response
     *
     * @throws ClientException
     * @throws RequestException
     * @throws SerializerException
     * @throws ValidatorException
     */
    public function updateUser(
        User $userModel,
        string $jwt
    ): Response
    {
        return $this
            ->getClient()
            ->request(
                (new Request(
                    '/data/users/%s',
                    'PUT'
                ))
                    ->setJson($userModel)
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
                            $userModel->getUid(),
                        ]
                    )
            );
    }

    /**
     * deleteUser
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
    public function deleteUser(
        string $uid,
        string $jwt
    ): Response
    {
        return $this
            ->getClient()
            ->request(
                (new Request(
                    '/data/users/%s',
                    'DELETE'
                ))
                    ->setJwt($jwt)
                    ->setUriParameters(
                        [
                            $uid,
                        ]
                    )
            );
    }
}
