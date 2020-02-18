<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Data\AuthToken\User;

use hunomina\DataValidator\Rule\Json\JsonRule;
use Jalismrs\Stalactite\Client\AbstractService;
use Jalismrs\Stalactite\Client\Data\AuthToken\JwtFactory;
use Jalismrs\Stalactite\Client\Data\Model\ModelFactory;
use Jalismrs\Stalactite\Client\Data\Schema;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\RequestException;
use Jalismrs\Stalactite\Client\Exception\SerializerException;
use Jalismrs\Stalactite\Client\Exception\ValidatorException;
use Jalismrs\Stalactite\Client\Util\Request;
use Jalismrs\Stalactite\Client\Util\Response;
use function array_map;

/**
 * Service
 *
 * @package Jalismrs\Stalactite\Service\Data\AuthToken\User
 */
class Service extends
    AbstractService
{
    /**
     * getAllUsers
     *
     * @param string $apiAuthToken
     *
     * @return Response
     *
     * @throws ClientException
     * @throws RequestException
     * @throws SerializerException
     * @throws ValidatorException
     */
    public function getAllUsers(
        string $apiAuthToken
    ): Response
    {
        $jwt = JwtFactory::generateJwt(
            $apiAuthToken,
            $this
                ->getClient()
                ->getUserAgent()
        );

        return $this
            ->getClient()
            ->request(
                (new Request(
                    '/data/auth-token/users'
                ))
                    ->setJwt((string)$jwt)
                    ->setResponseFormatter(
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
     * getByEmailAndGoogleId
     *
     * @param string $email
     * @param string $googleId
     * @param string $apiAuthToken
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
        string $apiAuthToken
    ): Response
    {
        $jwt = JwtFactory::generateJwt(
            $apiAuthToken,
            $this
                ->getClient()
                ->getUserAgent()
        );

        return $this
            ->getClient()
            ->request(
                (new Request(
                    '/data/auth-token/users'
                ))
                    ->setJwt((string)$jwt)
                    ->setQueryParameters(
                        [
                            'email' => $email,
                            'googleId' => $googleId
                        ]
                    )
                    ->setResponseFormatter(
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
     * getUser
     *
     * @param string $uid
     * @param string $apiAuthToken
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
        string $apiAuthToken
    ): Response
    {
        $jwt = JwtFactory::generateJwt(
            $apiAuthToken,
            $this
                ->getClient()
                ->getUserAgent()
        );

        return $this
            ->getClient()
            ->request(
                (new Request(
                    '/data/auth-token/users/%s'
                ))
                    ->setJwt((string)$jwt)
                    ->setResponseFormatter(
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
}
