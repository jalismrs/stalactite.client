<?php

namespace Jalismrs\Stalactite\Client\Data\User\Me\Subordinate;

use hunomina\DataValidator\Schema\Json\JsonSchema;
use Jalismrs\Stalactite\Client\AbstractService;
use Jalismrs\Stalactite\Client\Data\Model\ModelFactory;
use Jalismrs\Stalactite\Client\Data\Model\User;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Util\Endpoint;
use Jalismrs\Stalactite\Client\Util\Response;
use Lcobucci\JWT\Token;
use Psr\SimpleCache\InvalidArgumentException;

/**
 * Class Service
 *
 * @package Jalismrs\Stalactite\Client\Data\User\Me\Subordinate
 */
class Service extends
    AbstractService
{
    /**
     * @param Token $jwt
     *
     * @return Response
     * @throws ClientException
     * @throws InvalidArgumentException
     */
    public function all(Token $jwt) : Response
    {
        $endpoint = new Endpoint('/data/users/me/subordinates');
        $endpoint->setResponseValidationSchema(
            new JsonSchema(
                User::getSchema(),
                JsonSchema::LIST_TYPE
            )
        )
                 ->setResponseFormatter(
                     static function(array $response) : array {
                         return array_map(
                             static fn(array $user) : User => ModelFactory::createUser($user),
                             $response
                         );
                     }
                 );
        
        return $this->getClient()
                    ->request(
                        $endpoint,
                        [
                            'jwt' => (string)$jwt,
                        ]
                    );
    }
}
