<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Data\User\Me;

use hunomina\DataValidator\Schema\Json\JsonSchema;
use Jalismrs\Stalactite\Client\AbstractService;
use Jalismrs\Stalactite\Client\Data\Model\ModelFactory;
use Jalismrs\Stalactite\Client\Data\Model\Post;
use Jalismrs\Stalactite\Client\Data\Model\User;
use Jalismrs\Stalactite\Client\Data\Schema;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Util\Endpoint;
use Jalismrs\Stalactite\Client\Util\Response;

/**
 * Class Service
 * @package Jalismrs\Stalactite\Client\Data\User\Me
 */
class Service extends AbstractService
{
    /**
     * @param string $jwt
     * @return Response
     * @throws ClientException
     */
    public function getMe(string $jwt): Response
    {
        $endpoint = new Endpoint('/data/users/me');
        $endpoint->setResponseValidationSchema(new JsonSchema(Schema::USER))
            ->setResponseFormatter(
                static function (array $response): User {
                    return ModelFactory::createUser($response);
                }
            );

        return $this->getClient()->request($endpoint, [
            'jwt' => $jwt
        ]);
    }

    /**
     * @param string $jwt
     * @return Response
     * @throws ClientException
     */
    public function getMyPosts(string $jwt): Response
    {
        $endpoint = new Endpoint('/data/users/me/posts');
        $endpoint->setResponseValidationSchema(new JsonSchema(Schema::POST, JsonSchema::LIST_TYPE))
            ->setResponseFormatter(
                static function (array $response): array {
                    return array_map(static function (array $post): Post {
                        return ModelFactory::createPost($post);
                    }, $response);
                }
            );

        return $this->getClient()->request($endpoint, [
            'jwt' => $jwt
        ]);
    }

    /**
     * @param string $jwt
     * @return Response
     * @throws ClientException
     */
    public function getMyLeads(string $jwt): Response
    {
        $endpoint = new Endpoint('/data/users/me/leads');
        $endpoint->setResponseValidationSchema(new JsonSchema(Schema::POST, JsonSchema::LIST_TYPE))
            ->setResponseFormatter(
                static function (array $response): array {
                    return array_map(static function (array $post): Post {
                        return ModelFactory::createPost($post);
                    }, $response);
                }
            );

        return $this->getClient()->request($endpoint, [
            'jwt' => $jwt
        ]);
    }
}
