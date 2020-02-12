<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Access\AuthToken\User;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use Jalismrs\Stalactite\Client\AbstractService;
use Jalismrs\Stalactite\Client\Access\AuthToken\JwtFactory;
use Jalismrs\Stalactite\Client\Client;
use Jalismrs\Stalactite\Client\ClientException;
use Jalismrs\Stalactite\Client\Data\Model\User;
use Jalismrs\Stalactite\Client\Exception\RequestConfigurationException;
use Jalismrs\Stalactite\Client\RequestConfiguration;
use Jalismrs\Stalactite\Client\Response;
use Jalismrs\Stalactite\Client\Util\SerializerException;

/**
 * Service
 *
 * @package Jalismrs\Stalactite\Service\Access\AuthToken\User
 */
class Service extends
    AbstractService
{
    /**
     * Service constructor.
     *
     * @param Client $client
     *
     * @throws RequestConfigurationException
     */
    public function __construct(
        Client $client
    ) {
        parent::__construct(
            $client
        );
        
        $this->requestConfigurations = [
            'deleteRelationsByUser' => (new RequestConfiguration(
                '/access/auth-token/users/%s/relations'
            ))
                ->setMethod('DELETE'),
        ];
    }
    
    /**
     * deleteRelationsByUser
     *
     * @param User   $userModel
     * @param string $apiAuthToken
     *
     * @return Response
     *
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     * @throws SerializerException
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
                $this->requestConfigurations['deleteRelationsByUser'],
                [
                    $userModel->getUid(),
                ],
                [
                    'headers' => [
                        'X-API-TOKEN' => (string)$jwt
                    ]
                ]
            );
    }
}
