<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Access\User\Me;

use hunomina\DataValidator\Rule\Json\JsonRule;
use hunomina\DataValidator\Schema\Json\JsonSchema;
use Jalismrs\Stalactite\Client\AbstractService;
use Jalismrs\Stalactite\Client\Access\Model\AccessClearance;
use Jalismrs\Stalactite\Client\Access\Model\DomainUserRelation;
use Jalismrs\Stalactite\Client\Access\Model\ModelFactory;
use Jalismrs\Stalactite\Client\Access\Schema;
use Jalismrs\Stalactite\Client\Data\Model\Domain;
use Jalismrs\Stalactite\Client\Data\Schema as DataSchema;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\Service\AccessServiceException;
use Jalismrs\Stalactite\Client\Util\Endpoint;
use Jalismrs\Stalactite\Client\Util\Response;
use function array_map;

/**
 * Service
 *
 * @package Jalismrs\Stalactite\Service\Access\User\Me
 */
class Service extends AbstractService
{
    /**
     * @param string $jwt
     * @return Response
     * @throws ClientException
     */
    public function getRelations(string $jwt): Response
    {
        $schema = [
            'uid' => [
                'type' => JsonRule::STRING_TYPE
            ],
            'domain' => [
                'type' => JsonRule::OBJECT_TYPE,
                'schema' => DataSchema::DOMAIN
            ]
        ];

        $endpoint = new Endpoint('/access/users/me/relations');
        $endpoint->setResponseValidationSchema(new JsonSchema($schema, JsonSchema::LIST_TYPE))
            ->setResponseFormatter(static function (array $response): array {
                return array_map(
                    static fn(array $relation): DomainUserRelation => ModelFactory::createDomainUserRelation($relation),
                    $response
                );
            });

        return $this->getClient()->request($endpoint, [
            'jwt' => $jwt
        ]);
    }

    /**
     * @param Domain $domain
     * @param string $jwt
     * @return Response
     * @throws ClientException
     */
    public function getAccessClearance(Domain $domain, string $jwt): Response
    {
        if ($domain->getUid() === null) {
            throw new AccessServiceException('User lacks a uid', AccessServiceException::MISSING_DOMAIN_UID);
        }

        $endpoint = new Endpoint('/access/users/me/access/%s');
        $endpoint->setResponseValidationSchema(new JsonSchema(Schema::ACCESS_CLEARANCE))
            ->setResponseFormatter(static function (array $response): AccessClearance {
                return ModelFactory::createAccessClearance($response);
            });

        return $this->getClient()->request($endpoint, [
            'jwt' => $jwt,
            'uriParameters' => $domain->getUid()
        ]);
    }
}
