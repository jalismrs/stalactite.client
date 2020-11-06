<?php

namespace Jalismrs\Stalactite\Client\Data\Customer\Me\Relation;

use hunomina\DataValidator\Rule\Json\JsonRule;
use hunomina\DataValidator\Schema\Json\JsonSchema;
use Jalismrs\Stalactite\Client\AbstractService;
use Jalismrs\Stalactite\Client\Data\Model\Domain;
use Jalismrs\Stalactite\Client\Data\Model\DomainCustomerRelation;
use Jalismrs\Stalactite\Client\Data\Model\ModelFactory;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Util\Endpoint;
use Jalismrs\Stalactite\Client\Util\Response;
use Lcobucci\JWT\Token;
use Psr\SimpleCache\InvalidArgumentException;

/**
 * Class Service
 *
 * @package Jalismrs\Stalactite\Client\Data\Customer\Me\Relation
 */
class Service extends
    AbstractService
{
    /**
     * @param Token $jwt
     *
     * @return Response
     * @throws ClientException
     * @throws InvalidArgumentException
     */
    public function all(Token $jwt): Response
    {
        $schema = [
            'uid' => [
                'type' => JsonRule::STRING_TYPE,
            ],
            'domain' => [
                'type' => JsonRule::OBJECT_TYPE,
                'schema' => Domain::getSchema(),
            ],
        ];

        $endpoint = new Endpoint('/data/customers/me/relations');
        $endpoint->setResponseValidationSchema(
            new JsonSchema(
                $schema,
                JsonSchema::LIST_TYPE
            )
        )
            ->setResponseFormatter(
                static function (array $response): array {
                    return array_map(
                        static fn(array $relation
                        ): DomainCustomerRelation => ModelFactory::createDomainCustomerRelation($relation),
                        $response
                    );
                }
            );

        return $this->getClient()
            ->request(
                $endpoint,
                [
                    'jwt' => (string)$jwt,
                ]
            );
    }
}
