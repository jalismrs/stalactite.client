<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Access\AuthToken\User;

use Jalismrs\Stalactite\Client\AbstractService;
use Jalismrs\Stalactite\Client\Access\AuthToken\JwtFactory;
use Jalismrs\Stalactite\Client\Data\Model\User;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\RequestException;
use Jalismrs\Stalactite\Client\Exception\SerializerException;
use Jalismrs\Stalactite\Client\Exception\ValidatorException;
use Jalismrs\Stalactite\Client\Util\Request;
use Jalismrs\Stalactite\Client\Util\Response;

/**
 * Service
 *
 * @package Jalismrs\Stalactite\Service\Access\AuthToken\User
 */
class Service extends
    AbstractService
{
    /**
     * deleteRelationsByUser
     *
     * @param User   $userModel
     * @param string $apiAuthToken
     *
     * @return Response
     *
     * @throws ClientException
     * @throws RequestException
     * @throws SerializerException
     * @throws ValidatorException
     */
    public function deleteRelationsByUser(
        User $userModel,
        string $apiAuthToken
    ) : Response {
        $jwt = JwtFactory::generateJwt(
            $apiAuthToken,
            $this
                ->getClient()
                ->getUserAgent()
        );
        
        return $this
            ->getClient()
            ->request(
                (new Request(
                    '/access/auth-token/users/%s/relations',
                    'DELETE'
                ))
                    ->setJwt((string)$jwt)
                    ->setUriParameters(
                        [
                            $userModel->getUid(),
                        ]
                    )
            );
    }
}
