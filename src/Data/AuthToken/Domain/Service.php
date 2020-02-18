<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Data\AuthToken\Domain;

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
 * @package Jalismrs\Stalactite\Service\Data\AuthToken\Domain
 */
class Service extends
    AbstractService
{
    /**
     * getAllDomains
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
    public function getAllDomains(
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
                    '/data/auth-token/domains'
                ))
                    ->setJwt((string)$jwt)
                    ->setResponseFormatter(
                        static function (array $response): array {
                            return [
                                'domains' => array_map(
                                    static function ($domain) {
                                        return ModelFactory::createDomain($domain);
                                    },
                                    $response['domains']
                                ),
                            ];
                        }
                    )
                    ->setValidation(
                        [
                            'domains' => [
                                'type' => JsonRule::LIST_TYPE,
                                'schema' => Schema::DOMAIN,
                            ],
                        ]
                    )
            );
    }

    /**
     * getByNameAndApiKey
     *
     * @param string $name
     * @param string $apiKey
     * @param string $apiAuthToken
     *
     * @return Response
     *
     * @throws ClientException
     * @throws RequestException
     * @throws SerializerException
     * @throws ValidatorException
     */
    public function getByNameAndApiKey(
        string $name,
        string $apiKey,
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
                    '/data/auth-token/domains'
                ))
                    ->setJwt((string)$jwt)
                    ->setQueryParameters(
                        [
                            'name' => $name,
                            'apiKey' => $apiKey
                        ]
                    )
                    ->setResponseFormatter(
                        static function (array $response): array {
                            return [
                                'domains' => array_map(
                                    static function ($domain) {
                                        return ModelFactory::createDomain($domain);
                                    },
                                    $response['domains']
                                ),
                            ];
                        }
                    )
                    ->setValidation(
                        [
                            'domains' => [
                                'type' => JsonRule::LIST_TYPE,
                                'schema' => Schema::DOMAIN,
                            ],
                        ]
                    )
            );
    }

    /**
     * getByName
     *
     * @param string $name
     * @param string $apiAuthToken
     *
     * @return Response
     *
     * @throws ClientException
     * @throws RequestException
     * @throws SerializerException
     * @throws ValidatorException
     */
    public function getByName(
        string $name,
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
                    '/data/auth-token/domains'
                ))
                    ->setJwt((string)$jwt)
                    ->setQueryParameters(
                        [
                            'name' => $name
                        ]
                    )
                    ->setResponseFormatter(
                        static function (array $response): array {
                            return [
                                'domains' => array_map(
                                    static function ($domain) {
                                        return ModelFactory::createDomain($domain);
                                    },
                                    $response['domains']
                                ),
                            ];
                        }
                    )
                    ->setValidation(
                        [
                            'domains' => [
                                'type' => JsonRule::LIST_TYPE,
                                'schema' => Schema::DOMAIN,
                            ],
                        ]
                    )
            );
    }

    /**
     * getDomain
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
    public function getDomain(
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
                    '/data/auth-token/domains/%s'
                ))
                    ->setJwt((string)$jwt)
                    ->setResponseFormatter(
                        static function (array $response): array {
                            return [
                                'domain' => $response['domain'] === null
                                    ? null
                                    : ModelFactory::createDomain($response['domain']),
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
                            'domain' => [
                                'type' => JsonRule::OBJECT_TYPE,
                                'null' => true,
                                'schema' => Schema::DOMAIN,
                            ],
                        ]
                    )
            );
    }
}
