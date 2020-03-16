<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Access\AuthToken\Domain;

use hunomina\DataValidator\Rule\Json\JsonRule;
use hunomina\DataValidator\Schema\Json\JsonSchema;
use Jalismrs\Stalactite\Client\AbstractService;
use Jalismrs\Stalactite\Client\Access\AuthToken\JwtFactory;
use Jalismrs\Stalactite\Client\Access\ResponseFactory;
use Jalismrs\Stalactite\Client\Data\Model\Domain;
use Jalismrs\Stalactite\Client\Data\Schema as DataSchema;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\Service\AccessServiceException;
use Jalismrs\Stalactite\Client\Util\Endpoint;
use Jalismrs\Stalactite\Client\Util\Response;

/**
 * Service
 *
 * @package Jalismrs\Stalactite\Service\Access\AuthToken\Domain
 */
class Service extends AbstractService
{
    /**
     * @param Domain $domain
     * @param string $apiAuthToken
     * @return Response
     * @throws ClientException
     */
    public function deleteRelationsByDomain(Domain $domain, string $apiAuthToken): Response
    {
        if ($domain->getUid() === null) {
            throw new AccessServiceException('Domain lacks a uid', AccessServiceException::MISSING_DOMAIN_UID);
        }

        $jwt = JwtFactory::generateJwt($apiAuthToken, $this->getClient()->getUserAgent());

        $endpoint = new Endpoint('/access/auth-token/domains/%s/relations', 'DELETE');

        return $this->getClient()->request($endpoint, [
            'jwt' => (string)$jwt,
            'uriParameters' => [$domain->getUid()]
        ]);
    }

    /**
     * @param Domain $domainModel
     * @param string $apiAuthToken
     * @return Response
     * @throws ClientException
     */
    public function getRelations(Domain $domainModel, string $apiAuthToken): Response
    {
        if ($domainModel->getUid() === null) {
            throw new AccessServiceException('Domain lacks a uid', AccessServiceException::MISSING_DOMAIN_UID);
        }

        $jwt = JwtFactory::generateJwt($apiAuthToken, $this->getClient()->getUserAgent());

        $schema = [
            'users' => [
                'type' => JsonRule::LIST_TYPE,
                'schema' => [
                    'uid' => [
                        'type' => JsonRule::STRING_TYPE,
                    ],
                    'user' => [
                        'type' => JsonRule::OBJECT_TYPE,
                        'schema' => DataSchema::USER,
                    ],
                ],
            ],
            'customers' => [
                'type' => JsonRule::LIST_TYPE,
                'schema' => [
                    'uid' => [
                        'type' => JsonRule::STRING_TYPE
                    ],
                    'customer' => [
                        'type' => JsonRule::OBJECT_TYPE,
                        'schema' => DataSchema::CUSTOMER,
                    ],
                ],
            ]
        ];

        $endpoint = new Endpoint('/access/auth-token/domains/%s/relations');
        $endpoint->setResponseValidationSchema(new JsonSchema($schema))
            ->setResponseFormatter(ResponseFactory::domainGetRelations($domainModel));

        return $this->getClient()->request($endpoint, [
            'jwt' => (string)$jwt,
            'uriParameters' => [$domainModel->getUid()]
        ]);
    }
}
