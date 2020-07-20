<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Access\User;

use hunomina\DataValidator\Rule\Json\JsonRule;
use hunomina\DataValidator\Schema\Json\JsonSchema;
use Jalismrs\Stalactite\Client\AbstractService;
use Jalismrs\Stalactite\Client\Access\Model\AccessClearance;
use Jalismrs\Stalactite\Client\Access\Model\DomainUserRelation;
use Jalismrs\Stalactite\Client\Access\Model\ModelFactory;
use Jalismrs\Stalactite\Client\Data\Model\Domain;
use Jalismrs\Stalactite\Client\Data\Model\User;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\Service\AccessServiceException;
use Jalismrs\Stalactite\Client\Util\Endpoint;
use Jalismrs\Stalactite\Client\Util\Response;
use Lcobucci\JWT\Token;
use Psr\SimpleCache\InvalidArgumentException;
use function array_map;

/**
 * Service
 *
 * @package Jalismrs\Stalactite\Service\Access\User
 */
class Service extends AbstractService
{
    private ?Me\Service $serviceMe = null;

    /*
     * -------------------------------------------------------------------------
     * Clients -----------------------------------------------------------------
     * -------------------------------------------------------------------------
     */

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

    /*
     * -------------------------------------------------------------------------
     * API ---------------------------------------------------------------------
     * -------------------------------------------------------------------------
     */

    /**
     * @param User $user
     * @param Token $jwt
     * @return Response
     * @throws ClientException
     * @throws InvalidArgumentException
     */
    public function getRelations(User $user, Token $jwt): Response
    {
        if ($user->getUid() === null) {
            throw new AccessServiceException('User lacks a uid', AccessServiceException::MISSING_USER_UID);
        }

        $schema = [
            'uid' => [
                'type' => JsonRule::STRING_TYPE
            ],
            'domain' => [
                'type' => JsonRule::OBJECT_TYPE,
                'schema' => Domain::getSchema()
            ]
        ];

        $endpoint = new Endpoint('/access/users/%s/relations');
        $endpoint->setResponseValidationSchema(new JsonSchema($schema, JsonSchema::LIST_TYPE))
            ->setResponseFormatter(static function (array $response): array {
                return array_map(
                    static fn(array $relation): DomainUserRelation => ModelFactory::createDomainUserRelation($relation),
                    $response
                );
            });

        return $this->getClient()->request($endpoint, [
            'jwt' => (string)$jwt,
            'uriParameters' => [$user->getUid()]
        ]);
    }

    /**
     * @param User $user
     * @param Domain $domain
     * @param Token $jwt
     * @return Response
     * @throws ClientException
     * @throws InvalidArgumentException
     */
    public function getAccessClearance(User $user, Domain $domain, Token $jwt): Response
    {
        if ($user->getUid() === null) {
            throw new AccessServiceException('User lacks a uid', AccessServiceException::MISSING_USER_UID);
        }

        if ($domain->getUid() === null) {
            throw new AccessServiceException('Domain lacks a uid', AccessServiceException::MISSING_DOMAIN_UID);
        }

        $endpoint = new Endpoint('/access/users/%s/access/%s');
        $endpoint->setResponseValidationSchema(new JsonSchema(AccessClearance::getSchema()))
            ->setResponseFormatter(
                static fn(array $response): AccessClearance => ModelFactory::createAccessClearance($response)
            );

        return $this->getClient()->request($endpoint, [
            'jwt' => (string)$jwt,
            'uriParameters' => [
                $user->getUid(),
                $domain->getUid()
            ]
        ]);
    }

    /**
     * @param User $user
     * @param Token $jwt
     * @return Response
     * @throws ClientException
     * @throws InvalidArgumentException
     */
    public function deleteRelations(User $user, Token $jwt): Response
    {
        if ($user->getUid() === null) {
            throw new AccessServiceException('User lacks a uid', AccessServiceException::MISSING_USER_UID);
        }

        $endpoint = new Endpoint('/access/users/%s/relations', 'DELETE');

        return $this->getClient()->request($endpoint, [
            'jwt' => (string)$jwt,
            'uriParameters' => [$user->getUid()]
        ]);
    }
}
