<?php

namespace Jalismrs\Stalactite\Client\Data\User\Me\Access;

use hunomina\DataValidator\Schema\Json\JsonSchema;
use Jalismrs\Stalactite\Client\AbstractService;
use Jalismrs\Stalactite\Client\Data\Model\AccessClearance;
use Jalismrs\Stalactite\Client\Data\Model\Domain;
use Jalismrs\Stalactite\Client\Data\Model\ModelFactory;
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
    public function clearance(Domain $domain, Token $jwt): Response
    {
        if ($domain->getUid() === null) {
            throw new DataServiceException('Domain lacks a uid', DataServiceException::MISSING_DOMAIN_UID);
        }

        $endpoint = new Endpoint('/data/users/me/access/%s');
        $endpoint->setResponseValidationSchema(new JsonSchema(AccessClearance::getSchema()))
            ->setResponseFormatter(
                static fn(array $response): AccessClearance => ModelFactory::createAccessClearance($response)
            );

        return $this->getClient()->request($endpoint, [
            'jwt' => (string)$jwt,
            'uriParameters' => [$domain->getUid()]
        ]);
    }
}