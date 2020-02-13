<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Data\Customer\Me;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Rule\JsonRule;
use Jalismrs\Stalactite\Client\AbstractService;
use Jalismrs\Stalactite\Client\Data\Model\ModelFactory;
use Jalismrs\Stalactite\Client\Data\Schema;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\SerializerException;
use Jalismrs\Stalactite\Client\Util\Response;
use Jalismrs\Stalactite\Client\Util\Request;

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
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     * @throws SerializerException
     */
    public function getMe(
        string $jwt
    ) : Response {
        return $this
            ->getClient()
            ->request(
                (new Request(
                    '/data/customers/me'
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
                                'me' => $response['me'] === null
                                    ? null
                                    : ModelFactory::createCustomer($response['me']),
                            ];
                        }
                    )
                    ->setValidation(
                        [
                            'me' => [
                                'type'   => JsonRule::OBJECT_TYPE,
                                'null'   => true,
                                'schema' => Schema::CUSTOMER,
                            ],
                        ]
                    )
            );
    }
}
