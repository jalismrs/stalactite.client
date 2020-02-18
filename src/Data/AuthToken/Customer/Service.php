<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Data\AuthToken\Customer;

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
 * @package Jalismrs\Stalactite\Service\Data\AuthToken\Customer
 */
class Service extends
    AbstractService
{
    /**
     * getAllCustomers
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
    public function getAllCustomers(
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
                    '/data/auth-token/customers'
                ))
                    ->setJwt((string)$jwt)
                    ->setResponseFormatter(
                        static function (array $response): array {
                            return [
                                'customers' => array_map(
                                    static function ($customer) {
                                        return ModelFactory::createCustomer($customer);
                                    },
                                    $response['customers']
                                ),
                            ];
                        }
                    )
                    ->setValidation(
                        [
                            'customers' => [
                                'type' => JsonRule::LIST_TYPE,
                                'schema' => Schema::CUSTOMER,
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
                    '/data/auth-token/customers'
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
                                'customer' => $response['customer'] === null
                                    ? null
                                    : ModelFactory::createCustomer($response['customer']),
                            ];
                        }
                    )
                    ->setValidation(
                        [
                            'customer' => [
                                'type' => JsonRule::OBJECT_TYPE,
                                'null' => true,
                                'schema' => Schema::CUSTOMER,
                            ],
                        ]
                    )
            );
    }

    /**
     * getCustomer
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
    public function getCustomer(
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
                    '/data/auth-token/customers/%s'
                ))
                    ->setJwt((string)$jwt)
                    ->setResponseFormatter(
                        static function (array $response): array {
                            return [
                                'customer' => $response['customer'] === null
                                    ? null
                                    : ModelFactory::createCustomer($response['customer']),
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
                            'customer' => [
                                'type' => JsonRule::OBJECT_TYPE,
                                'null' => true,
                                'schema' => Schema::CUSTOMER,
                            ],
                        ]
                    )
            );
    }
}
