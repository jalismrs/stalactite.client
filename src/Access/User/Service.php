<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Access\User;

use hunomina\DataValidator\Rule\Json\JsonRule;
use hunomina\DataValidator\Schema\Json\JsonSchema;
use Jalismrs\Stalactite\Client\AbstractService;
use Jalismrs\Stalactite\Client\Access\Model\AccessClearance;
use Jalismrs\Stalactite\Client\Access\Model\DomainUserRelation;
use Jalismrs\Stalactite\Client\Access\Model\ModelFactory;
use Jalismrs\Stalactite\Client\Access\Schema;
use Jalismrs\Stalactite\Client\Data\Model\Domain;
use Jalismrs\Stalactite\Client\Data\Model\User;
use Jalismrs\Stalactite\Client\Data\Schema as DataSchema;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\Service\AccessServiceException;
use Jalismrs\Stalactite\Client\Util\Endpoint;
use Jalismrs\Stalactite\Client\Util\Response;
use function array_map;

/**
 * Service
 *
 * @package Jalismrs\Stalactite\Service\Access\User
 */
class Service extends AbstractService
{
    /**
     * @var Me\Service|null
     */
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
     * @param string $jwt
     * @return Response
     * @throws ClientException
     */
    public function getRelations(User $user, string $jwt): Response
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
                'schema' => DataSchema::DOMAIN
            ]
        ];

        $endpoint = new Endpoint('/access/users/%s/relations');
        $endpoint->setResponseValidationSchema(new JsonSchema($schema, JsonSchema::LIST_TYPE))
            ->setResponseFormatter(static function (array $response) use ($user) : array {
                return array_map(
                    static function (array $relation) use ($user): DomainUserRelation {
                        $domainUserRelationModel = ModelFactory::createDomainUserRelation($relation);
                        $domainUserRelationModel->setUser($user);

                        return $domainUserRelationModel;
                    },
                    $response
                );
            });

        return $this->getClient()->request($endpoint, [
            'jwt' => $jwt,
            'uriParameters' => [$user->getUid()]
        ]);
    }

    /**
     * @param User $user
     * @param Domain $domain
     * @param string $jwt
     * @return Response
     * @throws ClientException
     */
    public function getAccessClearance(User $user, Domain $domain, string $jwt): Response
    {
        if ($user->getUid() === null) {
            throw new AccessServiceException('User lacks a uid', AccessServiceException::MISSING_USER_UID);
        }

        if ($domain->getUid() === null) {
            throw new AccessServiceException('Domain lacks a uid', AccessServiceException::MISSING_DOMAIN_UID);
        }

        $endpoint = new Endpoint('/access/users/%s/access/%s');
        $endpoint->setResponseValidationSchema(new JsonSchema(Schema::ACCESS_CLEARANCE))
            ->setResponseFormatter(
                static fn(array $response): AccessClearance => ModelFactory::createAccessClearance($response)
            );

        return $this->getClient()->request($endpoint, [
            'jwt' => $jwt,
            'uriParameters' => [
                $user->getUid(),
                $domain->getUid(),
            ]
        ]);
    }
}
