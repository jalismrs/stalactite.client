<?php

namespace Jalismrs\Stalactite\Client\Data\User\Subordinate;

use hunomina\DataValidator\Schema\Json\JsonSchema;
use Jalismrs\Stalactite\Client\AbstractService;
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
 * @package Jalismrs\Stalactite\Client\Data\User\Subordinate
 */
class Service extends
    AbstractService
{
    /**
     * @param User $user
     * @param Token $jwt
     *
     * @return Response
     * @throws ClientException
     * @throws InvalidArgumentException
     */
    public function all(
        User $user,
        Token $jwt
    ): Response
    {
        if ($user->getUid() === null) {
            throw new DataServiceException(
                'User lacks an uid',
                DataServiceException::MISSING_USER_UID
            );
        }

        $endpoint = new Endpoint('/data/users/%s/subordinates');
        $endpoint->setResponseValidationSchema(
            new JsonSchema(
                User::getSchema(),
                JsonSchema::LIST_TYPE
            )
        )
            ->setResponseFormatter(
                static function (array $response): array {
                    return array_map(
                        static fn(array $user): User => ModelFactory::createUser($user),
                        $response
                    );
                }
            );

        return $this->getClient()
            ->request(
                $endpoint,
                [
                    'jwt' => $jwt->toString(),
                    'uriParameters' => [$user->getUid()],
                ]
            );
    }
}
