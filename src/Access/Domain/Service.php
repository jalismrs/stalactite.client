<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Access\Domain;

use hunomina\DataValidator\Rule\Json\JsonRule;
use hunomina\DataValidator\Schema\Json\JsonSchema;
use Jalismrs\Stalactite\Client\AbstractService;
use Jalismrs\Stalactite\Client\Access\Model\DomainCustomerRelation;
use Jalismrs\Stalactite\Client\Access\Model\DomainUserRelation;
use Jalismrs\Stalactite\Client\Access\Model\ModelFactory;
use Jalismrs\Stalactite\Client\Access\ResponseFactory;
use Jalismrs\Stalactite\Client\Access\Schema;
use Jalismrs\Stalactite\Client\Data\Model\Customer;
use Jalismrs\Stalactite\Client\Data\Model\Domain;
use Jalismrs\Stalactite\Client\Data\Model\User;
use Jalismrs\Stalactite\Client\Data\Schema as DataSchema;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\Service\AccessServiceException;
use Jalismrs\Stalactite\Client\Util\Endpoint;
use Jalismrs\Stalactite\Client\Util\Response;

/**
 * Service
 *
 * @package Jalismrs\Stalactite\Service\Access\Domain
 */
class Service extends AbstractService
{
    /**
     * @param Domain $domain
     * @param string $jwt
     * @return Response
     * @throws ClientException
     */
    public function getRelations(Domain $domain, string $jwt): Response
    {
        if ($domain->getUid() === null) {
            throw new AccessServiceException('Domain lacks a uid', AccessServiceException::MISSING_DOMAIN_UID);
        }

        $schema = [
            'users' => [
                'type' => JsonRule::LIST_TYPE,
                'schema' => [
                    'uid' => [
                        'type' => JsonRule::STRING_TYPE
                    ],
                    'user' => [
                        'type' => JsonRule::OBJECT_TYPE,
                        'schema' => DataSchema::USER
                    ]
                ]
            ],
            'customers' => [
                'type' => JsonRule::LIST_TYPE,
                'schema' => [
                    'uid' => [
                        'type' => JsonRule::STRING_TYPE
                    ],
                    'customer' => [
                        'type' => JsonRule::OBJECT_TYPE,
                        'schema' => DataSchema::CUSTOMER
                    ]
                ]
            ]
        ];

        $endpoint = new Endpoint('/access/domains/%s/relations');
        $endpoint->setResponseValidationSchema(new JsonSchema($schema))
            ->setResponseFormatter(ResponseFactory::domainGetRelations($domain));

        return $this->getClient()->request($endpoint, [
            'jwt' => $jwt,
            'uriParameters' => [$domain->getUid()]
        ]);
    }

    /**
     * @param Domain $domain
     * @param User $user
     * @param string $jwt
     * @return Response
     * @throws ClientException
     */
    public function addUserRelation(Domain $domain, User $user, string $jwt): Response
    {
        if ($domain->getUid() === null) {
            throw new AccessServiceException('Domain lacks a uid', AccessServiceException::MISSING_DOMAIN_UID);
        }

        if ($user->getUid() === null) {
            throw new AccessServiceException('User lacks a uid', AccessServiceException::MISSING_USER_UID);
        }

        $endpoint = new Endpoint('/access/domains/%s/relations/users', 'POST');
        $endpoint->setResponseValidationSchema(new JsonSchema(Schema::DOMAIN_USER_RELATION))
            ->setResponseFormatter(
                static fn(array $response): DomainUserRelation => ModelFactory::createDomainUserRelation($response)
            );

        return $this->getClient()->request($endpoint, [
            'jwt' => $jwt,
            'uriParameters' => [$domain->getUid()],
            'json' => ['user' => $user->getUid()]
        ]);
    }

    /**
     * @param Domain $domain
     * @param Customer $customer
     * @param string $jwt
     * @return Response
     * @throws ClientException
     */
    public function addCustomerRelation(Domain $domain, Customer $customer, string $jwt): Response
    {
        if ($domain->getUid() === null) {
            throw new AccessServiceException('Domain lacks a uid', AccessServiceException::MISSING_DOMAIN_UID);
        }

        if ($customer->getUid() === null) {
            throw new AccessServiceException('Customer lacks a uid', AccessServiceException::MISSING_CUSTOMER_UID);
        }

        $endpoint = new Endpoint('/access/domains/%s/relations/customers', 'POST');
        $endpoint->setResponseValidationSchema(new JsonSchema(Schema::DOMAIN_CUSTOMER_RELATION))
            ->setResponseFormatter(
                static fn(array $response): DomainCustomerRelation => ModelFactory::createDomainCustomerRelation($response)
            );

        return $this->getClient()->request($endpoint, [
            'jwt' => $jwt,
            'uriParameters' => [$domain->getUid()],
            'json' => ['customer' => $customer->getUid()]
        ]);
    }
}
