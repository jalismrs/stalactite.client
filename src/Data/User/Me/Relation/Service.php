<?php

namespace Jalismrs\Stalactite\Client\Data\User\Me\Relation;

use hunomina\DataValidator\Rule\Json\JsonRule;
use hunomina\DataValidator\Schema\Json\JsonSchema;
use Jalismrs\Stalactite\Client\AbstractService;
use Jalismrs\Stalactite\Client\Data\Model\Domain;
use Jalismrs\Stalactite\Client\Data\Model\DomainUserRelation;
use Jalismrs\Stalactite\Client\Data\Model\ModelFactory;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Util\Endpoint;
use Jalismrs\Stalactite\Client\Util\Response;
use Lcobucci\JWT\Token;
use Psr\SimpleCache\InvalidArgumentException;

class Service extends AbstractService
{
    /**
     * @param Token $jwt
     * @return Response
     * @throws ClientException
     * @throws InvalidArgumentException
     */
    public function all(Token $jwt): Response
    {
        $schema = [
            'uid' => [
                'type' => JsonRule::STRING_TYPE
            ],
            'domain' => [
                'type' => JsonRule::OBJECT_TYPE,
                'schema' => Domain::getSchema()
            ]
        ];

        $endpoint = new Endpoint('/data/users/me/relations');
        $endpoint->setResponseValidationSchema(new JsonSchema($schema, JsonSchema::LIST_TYPE))
            ->setResponseFormatter(static function (array $response): array {
                return array_map(
                    static fn(array $relation): DomainUserRelation => ModelFactory::createDomainUserRelation($relation),
                    $response
                );
            });

        return $this->getClient()->request($endpoint, [
            'jwt' => (string)$jwt
        ]);
    }
}