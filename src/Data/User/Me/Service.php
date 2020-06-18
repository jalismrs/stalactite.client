<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Data\User\Me;

use hunomina\DataValidator\Schema\Json\JsonSchema;
use Jalismrs\Stalactite\Client\AbstractService;
use Jalismrs\Stalactite\Client\Data\Model\ModelFactory;
use Jalismrs\Stalactite\Client\Data\Model\Post;
use Jalismrs\Stalactite\Client\Data\Model\User;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Util\Endpoint;
use Jalismrs\Stalactite\Client\Util\Response;
use Lcobucci\JWT\Token;

/**
 * Class Service
 * @package Jalismrs\Stalactite\Client\Data\User\Me
 */
class Service extends AbstractService
{
    /**
     * @param Token $jwt
     * @return Response
     * @throws ClientException
     */
    public function get(Token $jwt): Response
    {
        $endpoint = new Endpoint('/data/users/me');
        $endpoint->setResponseValidationSchema(new JsonSchema(User::getSchema()))
            ->setResponseFormatter(static fn(array $response): User => ModelFactory::createUser($response));

        return $this->getClient()->request($endpoint, [
            'jwt' => (string)$jwt
        ]);
    }

    /**
     * @param Token $jwt
     * @return Response
     * @throws ClientException
     */
    public function getPosts(Token $jwt): Response
    {
        $endpoint = new Endpoint('/data/users/me/posts');
        $endpoint->setResponseValidationSchema(new JsonSchema(Post::getSchema(), JsonSchema::LIST_TYPE))
            ->setResponseFormatter(static function (array $response): array {
                return array_map(static fn(array $post): Post => ModelFactory::createPost($post), $response);
            });

        return $this->getClient()->request($endpoint, [
            'jwt' => (string)$jwt
        ]);
    }

    /**
     * @param Token $jwt
     * @return Response
     * @throws ClientException
     */
    public function getLeads(Token $jwt): Response
    {
        $endpoint = new Endpoint('/data/users/me/leads');
        $endpoint->setResponseValidationSchema(new JsonSchema(Post::getSchema(), JsonSchema::LIST_TYPE))
            ->setResponseFormatter(static function (array $response): array {
                return array_map(static fn(array $post): Post => ModelFactory::createPost($post), $response);
            });

        return $this->getClient()->request($endpoint, [
            'jwt' => (string)$jwt
        ]);
    }

    /**
     * @param Token $jwt
     * @return Response
     * @throws ClientException
     */
    public function getSubordinates(Token $jwt): Response
    {
        $endpoint = new Endpoint('/data/users/me/subordinates');
        $endpoint->setResponseValidationSchema(new JsonSchema(User::getSchema(), JsonSchema::LIST_TYPE))
            ->setResponseFormatter(static function (array $response): array {
                return array_map(static fn(array $user): User => ModelFactory::createUser($user), $response);
            });

        return $this->getClient()->request($endpoint, [
            'jwt' => (string)$jwt
        ]);
    }
}
