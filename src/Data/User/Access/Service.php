<?php

namespace Jalismrs\Stalactite\Client\Data\User\Access;

use hunomina\DataValidator\Schema\Json\JsonSchema;
use Jalismrs\Stalactite\Client\AbstractService;
use Jalismrs\Stalactite\Client\Data\Model\AccessClearance;
use Jalismrs\Stalactite\Client\Data\Model\Domain;
use Jalismrs\Stalactite\Client\Data\Model\ModelFactory;
use Jalismrs\Stalactite\Client\Data\Model\User;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\Service\DataServiceException;
use Jalismrs\Stalactite\Client\Util\Endpoint;
use Jalismrs\Stalactite\Client\Util\Response;
use Lcobucci\JWT\Token;
use Psr\SimpleCache\InvalidArgumentException;

/**
 * Class Service
 *
 * @package Jalismrs\Stalactite\Client\Data\User\Access
 */
class Service extends
    AbstractService
{
    /**
     * @param User $user
     * @param Domain $domain
     * @param Token $jwt
     *
     * @return Response
     * @throws ClientException
     * @throws InvalidArgumentException
     */
    public function clearance(
        User $user,
        Domain $domain,
        Token $jwt
    ): Response
    {
        if ($user->getUid() === null) {
            throw new DataServiceException(
                'User lacks a uid',
                DataServiceException::MISSING_USER_UID
            );
        }

        if ($domain->getUid() === null) {
            throw new DataServiceException(
                'Domain lacks a uid',
                DataServiceException::MISSING_DOMAIN_UID
            );
        }

        $endpoint = new Endpoint('/data/users/%s/access/%s');
        $endpoint->setResponseValidationSchema(new JsonSchema(AccessClearance::getSchema()))
            ->setResponseFormatter(
                static fn(array $response): AccessClearance => ModelFactory::createAccessClearance($response)
            );

        return $this->getClient()
            ->request(
                $endpoint,
                [
                    'jwt' => $jwt->toString(),
                    'uriParameters' => [
                        $user->getUid(),
                        $domain->getUid(),
                    ],
                ]
            );
    }
}
