<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Data\User\Post;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Rule\JsonRule;
use InvalidArgumentException;
use Jalismrs\Stalactite\Client\AbstractService;
use Jalismrs\Stalactite\Client\Client;
use Jalismrs\Stalactite\Client\ClientException;
use Jalismrs\Stalactite\Client\Data\Model\ModelFactory;
use Jalismrs\Stalactite\Client\Data\Model\Post;
use Jalismrs\Stalactite\Client\Data\Model\User;
use Jalismrs\Stalactite\Client\Data\Schema;
use Jalismrs\Stalactite\Client\Exception\RequestConfigurationException;
use Jalismrs\Stalactite\Client\RequestConfiguration;
use Jalismrs\Stalactite\Client\Response;
use Jalismrs\Stalactite\Client\Util\ModelHelper;
use Jalismrs\Stalactite\Client\Util\SerializerException;
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
            'addPosts'    => (new RequestConfiguration(
                '/data/users/%s/posts'
            ))
                ->setMethod('POST'),
            'getAll'      => (new RequestConfiguration(
                '/data/users/%s/posts'
            ))
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
                ->setValidation(
                    [
                        'posts' => [
                            'type'   => JsonRule::LIST_TYPE,
                            'schema' => Schema::POST,
                        ],
                    ]
                ),
            'removePosts' => (new RequestConfiguration(
                '/data/users/%s/posts'
            ))
                ->setMethod('DELETE'),
        ];
    }
    
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
                $this->requestConfigurations['getAll'],
                [
                    $userModel->getUid(),
                ],
                [
                    'headers' => [
                        'X-API-TOKEN' => $jwt
                    ]
                ]
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
                $this->requestConfigurations['addPosts'],
                [
                    $userModel->getUid(),
                ],
                [
                    'headers' => [
                        'X-API-TOKEN' => $jwt
                    ],
                    'json'    => [
                        'posts' => ModelHelper::getUids(
                            $postModels,
                            Post::class
                        )
                    ],
                ]
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
                $this->requestConfigurations['removePosts'],
                [
                    $userModel->getUid(),
                ],
                [
                    'headers' => [
                        'X-API-TOKEN' => $jwt
                    ],
                    'json'    => [
                        'posts' => ModelHelper::getUids(
                            $postModels,
                            Post::class
                        )
                    ],
                ]
            );
    }
}
