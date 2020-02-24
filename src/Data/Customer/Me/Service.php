<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Data\Customer\Me;

use hunomina\DataValidator\Rule\Json\JsonRule;
use Jalismrs\Stalactite\Client\AbstractService;
use Jalismrs\Stalactite\Client\Data\Model\ModelFactory;
use Jalismrs\Stalactite\Client\Data\Schema;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\RequestException;
use Jalismrs\Stalactite\Client\Exception\SerializerException;
use Jalismrs\Stalactite\Client\Exception\ValidatorException;
use Jalismrs\Stalactite\Client\Util\Request;
use Jalismrs\Stalactite\Client\Util\Response;

/**
 * Service
 *
 * @package Jalismrs\Stalactite\Service\Data\Customer\Me
 */
class Service extends
    AbstractService
{
    /**
     * getMe
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
    public function getMe(
        string $jwt
    ): Response
    {
        return $this
            ->getClient()
            ->request(
                (new Request(
                    '/data/customers/me'
                ))
                    ->setJwt($jwt)
                    ->setResponseFormatter(
                        static function (array $response): array {
                            return [
                                'me' => $response['me'] === null
                                    ? null
                                    : ModelFactory::createCustomer($response['me']),
                            ];
                        }
                    )
                    ->setValidation(
                        [
                            'me' => [
                                'type' => JsonRule::OBJECT_TYPE,
                                'null' => true,
                                'schema' => Schema::CUSTOMER,
                            ],
                        ]
                    )
            );
    }
}
