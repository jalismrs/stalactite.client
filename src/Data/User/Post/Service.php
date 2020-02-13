<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Data\User\Post;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Rule\JsonRule;
use InvalidArgumentException;
use Jalismrs\Stalactite\Client\AbstractService;
use Jalismrs\Stalactite\Client\Data\Model\ModelFactory;
use Jalismrs\Stalactite\Client\Data\Model\Post;
use Jalismrs\Stalactite\Client\Data\Model\User;
use Jalismrs\Stalactite\Client\Data\Schema;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\SerializerException;
use Jalismrs\Stalactite\Client\Util\ModelHelper;
use Jalismrs\Stalactite\Client\Util\Request;
use Jalismrs\Stalactite\Client\Util\Response;
use function array_map;

/**
 * Service
 *
 * @package Jalismrs\Stalactite\Service\Data\User\Post
 */
class Service extends
    AbstractService
{
    /**
     * getAllPosts
     *
     * @param User   $userModel
     * @param string $jwt
     *
     * @return Response
     *
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     * @throws SerializerException
     */
    public function getAllPosts(
        User $userModel,
        string $jwt
    ) : Response {
        return $this
            ->getClient()
            ->request(
                (new Request(
                    '/data/users/%s/posts'
                ))
                    ->setJwt($jwt)
                    ->setResponse(
                        static function(array $response) : array {
                            return [
                                'posts' => array_map(
                                    static function($post) {
                                        return ModelFactory::createPost($post);
                                    },
                                    $response['posts']
                                ),
                            ];
                        }
                    )
                    ->setUriParameters(
                        [
                            $userModel->getUid(),
                        ]
                    )
                    ->setValidation(
                        [
                            'posts' => [
                                'type'   => JsonRule::LIST_TYPE,
                                'schema' => Schema::POST,
                            ],
                        ]
                    )
            );
    }
    
    /**
     * addPosts
     *
     * @param User   $userModel
     * @param array  $postModels
     * @param string $jwt
     *
     * @return Response
     *
     * @throws ClientException
     * @throws InvalidArgumentException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     * @throws SerializerException
     */
    public function addPosts(
        User $userModel,
        array $postModels,
        string $jwt
    ) : Response {
        return $this
            ->getClient()
            ->request(
                (new Request(
                    '/data/users/%s/posts',
                    'POST'
                ))
                    ->setJson(
                        [
                            'posts' => ModelHelper::getUids(
                                $postModels,
                                Post::class
                            )
                        ]
                    )
                    ->setJwt($jwt)
                    ->setUriParameters(
                        [
                            $userModel->getUid(),
                        ]
                    )
            );
    }
    
    /**
     * removePosts
     *
     * @param User   $userModel
     * @param array  $postModels
     * @param string $jwt
     *
     * @return Response
     *
     * @throws ClientException
     * @throws InvalidArgumentException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     * @throws SerializerException
     */
    public function removePosts(
        User $userModel,
        array $postModels,
        string $jwt
    ) : Response {
        return $this
            ->getClient()
            ->request(
                (new Request(
                    '/data/users/%s/posts',
                    'DELETE'
                ))
                    ->setJson(
                        [
                            'posts' => ModelHelper::getUids(
                                $postModels,
                                Post::class
                            )
                        ]
                    )
                    ->setJwt($jwt)
                    ->setUriParameters(
                        [
                            $userModel->getUid(),
                        ]
                    )
            );
    }
}
