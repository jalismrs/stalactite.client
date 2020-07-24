<?php

namespace Jalismrs\Stalactite\Client\Data\Domain\Relation;

use hunomina\DataValidator\Rule\Json\JsonRule;
use hunomina\DataValidator\Schema\Json\JsonSchema;
use Jalismrs\Stalactite\Client\AbstractService;
use Jalismrs\Stalactite\Client\Data\Model\Customer;
use Jalismrs\Stalactite\Client\Data\Model\Domain;
use Jalismrs\Stalactite\Client\Data\Model\DomainCustomerRelation;
use Jalismrs\Stalactite\Client\Data\Model\DomainUserRelation;
use Jalismrs\Stalactite\Client\Data\Model\ModelFactory;
use Jalismrs\Stalactite\Client\Data\Model\User;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\Service\DataServiceException;
use Jalismrs\Stalactite\Client\Util\Endpoint;
use Jalismrs\Stalactite\Client\Util\Response;
use Lcobucci\JWT\Token;
use Psr\SimpleCache\InvalidArgumentException;

class Service extends AbstractService
{
    /**
     * @param Domain $domain
     * @param Token $jwt
     * @return Response
     * @throws ClientException
     * @throws InvalidArgumentException
     */
    public function all(Domain $domain, Token $jwt): Response
    {
        if ($domain->getUid() === null) {
            throw new DataServiceException('Domain lacks a uid', DataServiceException::MISSING_DOMAIN_UID);
        }

        $schema = [
            'users' => ['type' => JsonRule::LIST_TYPE, 'schema' => [
                'uid' => ['type' => JsonRule::STRING_TYPE],
                'user' => ['type' => JsonRule::OBJECT_TYPE, 'schema' => User::getSchema()]
            ]],
            'customers' => ['type' => JsonRule::LIST_TYPE, 'schema' => [
                'uid' => ['type' => JsonRule::STRING_TYPE],
                'customer' => ['type' => JsonRule::OBJECT_TYPE, 'schema' => Customer::getSchema()]
            ]]
        ];

        $endpoint = new Endpoint('/data/domains/%s/relations');
        $endpoint->setResponseValidationSchema(new JsonSchema($schema))
            ->setResponseFormatter(static function (array $response) use ($domain) : array {
                return [
                    'users' => array_map(
                        static function (array $relation) use ($domain): DomainUserRelation {
                            $domainUserRelation = ModelFactory::createDomainUserRelation($relation);
                            $domainUserRelation->setDomain($domain);

                            return $domainUserRelation;
                        },
                        $response['users']
                    ),
                    'customers' => array_map(
                        static function (array $relation) use ($domain): DomainCustomerRelation {
                            $domainCustomerRelation = ModelFactory::createDomainCustomerRelation($relation);
                            $domainCustomerRelation->setDomain($domain);

                            return $domainCustomerRelation;
                        },
                        $response['customers']
                    )
                ];
            });

        return $this->getClient()->request($endpoint, [
            'jwt' => (string)$jwt,
            'uriParameters' => [$domain->getUid()]
        ]);
    }

    /**
     * @param Domain $domain
     * @param User $user
     * @param Token $jwt
     * @return Response
     * @throws ClientException
     * @throws InvalidArgumentException
     */
    public function addUserRelation(Domain $domain, User $user, Token $jwt): Response
    {
        if ($domain->getUid() === null) {
            throw new DataServiceException('Domain lacks a uid', DataServiceException::MISSING_DOMAIN_UID);
        }

        if ($user->getUid() === null) {
            throw new DataServiceException('User lacks a uid', DataServiceException::MISSING_USER_UID);
        }

        $endpoint = new Endpoint('/data/domains/%s/relations/users', 'POST');
        $endpoint->setResponseValidationSchema(new JsonSchema(DomainUserRelation::getSchema()))
            ->setResponseFormatter(
                static fn(array $response): DomainUserRelation => ModelFactory::createDomainUserRelation($response)
            );

        return $this->getClient()->request($endpoint, [
            'jwt' => (string)$jwt,
            'uriParameters' => [$domain->getUid()],
            'json' => ['user' => $user->getUid()]
        ]);
    }

    /**
     * @param Domain $domain
     * @param Customer $customer
     * @param Token $jwt
     * @return Response
     * @throws ClientException
     * @throws InvalidArgumentException
     */
    public function addCustomerRelation(Domain $domain, Customer $customer, Token $jwt): Response
    {
        if ($domain->getUid() === null) {
            throw new DataServiceException('Domain lacks a uid', DataServiceException::MISSING_DOMAIN_UID);
        }

        if ($customer->getUid() === null) {
            throw new DataServiceException('Customer lacks a uid', DataServiceException::MISSING_CUSTOMER_UID);
        }

        $endpoint = new Endpoint('/data/domains/%s/relations/customers', 'POST');
        $endpoint->setResponseValidationSchema(new JsonSchema(DomainCustomerRelation::getSchema()))
            ->setResponseFormatter(
                static fn(array $response): DomainCustomerRelation => ModelFactory::createDomainCustomerRelation($response)
            );

        return $this->getClient()->request($endpoint, [
            'jwt' => (string)$jwt,
            'uriParameters' => [$domain->getUid()],
            'json' => ['customer' => $customer->getUid()]
        ]);
    }

    /**
     * @param Domain $domain
     * @param Token $jwt
     * @return Response
     * @throws ClientException
     * @throws InvalidArgumentException
     */
    public function deleteAll(Domain $domain, Token $jwt): Response
    {
        if ($domain->getUid() === null) {
            throw new DataServiceException('Domain lacks a uid', DataServiceException::MISSING_DOMAIN_UID);
        }

        $endpoint = new Endpoint('/data/domains/%s/relations', 'DELETE');

        return $this->getClient()->request($endpoint, [
            'jwt' => (string)$jwt,
            'uriParameters' => [$domain->getUid()]
        ]);
    }
}