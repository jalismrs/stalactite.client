<?php

namespace Jalismrs\Stalactite\Client\Data\User\Me\Lead;

use hunomina\DataValidator\Schema\Json\JsonSchema;
use Jalismrs\Stalactite\Client\AbstractService;
use Jalismrs\Stalactite\Client\Data\Model\ModelFactory;
use Jalismrs\Stalactite\Client\Data\Model\Post;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Util\Endpoint;
use Jalismrs\Stalactite\Client\Util\Response;
use Lcobucci\JWT\Token;
use Psr\SimpleCache\InvalidArgumentException;

/**
 * Class Service
 *
 * @package Jalismrs\Stalactite\Client\Data\User\Me\Lead
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
        $endpoint = new Endpoint('/data/users/me/leads');
        $endpoint->setResponseValidationSchema(
            new JsonSchema(
                Post::getSchema(),
                JsonSchema::LIST_TYPE
            )
        )
                 ->setResponseFormatter(
                     static function(array $response) : array {
                         return array_map(
                             static fn(array $post) : Post => ModelFactory::createPost($post),
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
