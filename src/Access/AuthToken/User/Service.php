<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Access\AuthToken\User;

use Jalismrs\Stalactite\Client\AbstractService;
use Jalismrs\Stalactite\Client\Access\AuthToken\JwtFactory;
use Jalismrs\Stalactite\Client\Data\Model\User;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\Service\AccessServiceException;
use Jalismrs\Stalactite\Client\Util\Endpoint;
use Jalismrs\Stalactite\Client\Util\Response;

/**
 * Service
 *
 * @package Jalismrs\Stalactite\Service\Access\AuthToken\User
 */
class Service extends AbstractService
{
    /**
     * @param User $user
     * @param string $apiAuthToken
     * @return Response
     * @throws ClientException
     */
    public function deleteRelationsByUser(User $user, string $apiAuthToken): Response
    {
        if ($user->getUid() === null) {
            throw new AccessServiceException('User lacks a uid', AccessServiceException::MISSING_USER_UID);
        }

        $jwt = JwtFactory::generateJwt($apiAuthToken, $this->getClient()->getUserAgent());

        $endpoint = new Endpoint('/access/auth-token/users/%s/relations', 'DELETE');

        return $this->getClient()->request($endpoint, [
            'jwt' => (string)$jwt,
            'uriParameters' => [$user->getUid()]
        ]);
    }
}
