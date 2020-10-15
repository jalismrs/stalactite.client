<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Data\User\Post;

use hunomina\DataValidator\Schema\Json\JsonSchema;
use InvalidArgumentException;
use Jalismrs\Stalactite\Client\AbstractService;
use Jalismrs\Stalactite\Client\Data\Model\ModelFactory;
use Jalismrs\Stalactite\Client\Data\Model\Post;
use Jalismrs\Stalactite\Client\Data\Model\User;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\Service\DataServiceException;
use Jalismrs\Stalactite\Client\Util\Endpoint;
use Jalismrs\Stalactite\Client\Util\ModelHelper;
use Jalismrs\Stalactite\Client\Util\Response;
use Lcobucci\JWT\Token;
use function array_map;

/**
 * Class Service
 *
 * @package Jalismrs\Stalactite\Client\Data\User\Post
 */
class Service extends
    AbstractService
{
    /**
     * @param User  $user
     * @param Token $jwt
     *
     * @return Response
     * @throws ClientException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function get(
        User $user,
        Token $jwt
    ) : Response {
        // TODO: rename 'all'
        
        if ($user->getUid() === null) {
            throw new DataServiceException(
                'User lacks an uid',
                DataServiceException::MISSING_USER_UID
            );
        }
        
        $endpoint = new Endpoint('/data/users/%s/posts');
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
                            'jwt'           => (string)$jwt,
                            'uriParameters' => [$user->getUid()],
                        ]
                    );
    }
    
    /**
     * @param User  $user
     * @param array $posts
     * @param Token $jwt
     *
     * @return Response
     * @throws ClientException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function add(
        User $user,
        array $posts,
        Token $jwt
    ) : ?Response {
        if ($user->getUid() === null) {
            throw new DataServiceException(
                'User lacks an uid',
                DataServiceException::MISSING_USER_UID
            );
        }
        
        if (count($posts) === 0) {
            return null;
        }
        
        try {
            $posts = ModelHelper::getUids(
                $posts,
                Post::class
            );
        } catch (InvalidArgumentException $e) {
            $this->getLogger()
                 ->error($e);
            throw new DataServiceException(
                'Error while getting posts uids',
                DataServiceException::INVALID_MODEL,
                $e
            );
        }
        
        $endpoint = new Endpoint(
            '/data/users/%s/posts',
            'POST'
        );
        
        return $this->getClient()
                    ->request(
                        $endpoint,
                        [
                            'jwt'           => (string)$jwt,
                            'json'          => ['posts' => $posts],
                            'uriParameters' => [$user->getUid()],
                        ]
                    );
    }
    
    /**
     * @param User  $user
     * @param array $posts
     * @param Token $jwt
     *
     * @return Response
     * @throws ClientException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function remove(
        User $user,
        array $posts,
        Token $jwt
    ) : ?Response {
        if ($user->getUid() === null) {
            throw new DataServiceException(
                'User lacks an uid',
                DataServiceException::MISSING_USER_UID
            );
        }
        
        if (count($posts) === 0) {
            return null;
        }
        
        try {
            $posts = ModelHelper::getUids(
                $posts,
                Post::class
            );
        } catch (InvalidArgumentException $e) {
            $this->getLogger()
                 ->error($e);
            throw new DataServiceException(
                'Error while getting posts uids',
                DataServiceException::INVALID_MODEL,
                $e
            );
        }
        
        $endpoint = new Endpoint(
            '/data/users/%s/posts',
            'DELETE'
        );
        
        return $this->getClient()
                    ->request(
                        $endpoint,
                        [
                            'jwt'           => (string)$jwt,
                            'json'          => ['posts' => $posts],
                            'uriParameters' => [$user->getUid()],
                        ]
                    );
    }
}
