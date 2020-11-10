<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Data\User;

use hunomina\DataValidator\Schema\Json\JsonSchema;
use InvalidArgumentException;
use Jalismrs\Stalactite\Client\AbstractService;
use Jalismrs\Stalactite\Client\Data\Model\ModelFactory;
use Jalismrs\Stalactite\Client\Data\Model\Post;
use Jalismrs\Stalactite\Client\Data\Model\User;
use Jalismrs\Stalactite\Client\Data\User\Post\Service as PostService;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\NormalizerException;
use Jalismrs\Stalactite\Client\Exception\Service\DataServiceException;
use Jalismrs\Stalactite\Client\PaginationMetadataTrait;
use Jalismrs\Stalactite\Client\Util\Endpoint;
use Jalismrs\Stalactite\Client\Util\ModelHelper;
use Jalismrs\Stalactite\Client\Util\Normalizer;
use Jalismrs\Stalactite\Client\Util\Response;
use Lcobucci\JWT\Token;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use function array_map;
use function array_merge;

/**
 * Class Service
 *
 * @package Jalismrs\Stalactite\Client\Data\User
 */
class Service extends
    AbstractService
{
    use PaginationMetadataTrait;

    private ?Lead\Service        $serviceLead = null;
    private ?Me\Service          $serviceMe = null;
    private ?PostService         $servicePost = null;
    private ?Access\Service      $serviceAccess = null;
    private ?Relation\Service    $serviceRelation = null;
    private ?Subordinate\Service $serviceSubordinate = null;

    /*
     * -------------------------------------------------------------------------
     * Clients -----------------------------------------------------------------
     * -------------------------------------------------------------------------
     */

    public function leads(): Lead\Service
    {
        if ($this->serviceLead === null) {
            $this->serviceLead = new Lead\Service($this->getClient());
        }

        return $this->serviceLead;
    }

    public function me(): Me\Service
    {
        if ($this->serviceMe === null) {
            $this->serviceMe = new Me\Service($this->getClient());
        }

        return $this->serviceMe;
    }

    public function posts(): PostService
    {
        if ($this->servicePost === null) {
            $this->servicePost = new PostService($this->getClient());
        }

        return $this->servicePost;
    }

    public function access(): Access\Service
    {
        if ($this->serviceAccess === null) {
            $this->serviceAccess = new Access\Service($this->getClient());
        }

        return $this->serviceAccess;
    }

    public function relations(): Relation\Service
    {
        if ($this->serviceRelation === null) {
            $this->serviceRelation = new Relation\Service($this->getClient());
        }

        return $this->serviceRelation;
    }

    public function subordinates(): Subordinate\Service
    {
        if ($this->serviceSubordinate === null) {
            $this->serviceSubordinate = new Subordinate\Service($this->getClient());
        }

        return $this->serviceSubordinate;
    }

    /*
     * -------------------------------------------------------------------------
     * API ---------------------------------------------------------------------
     * -------------------------------------------------------------------------
     */

    /**
     * @param Token $jwt
     * @param int $page
     * @return Response
     * @throws ClientException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function all(Token $jwt, int $page = 1): Response
    {
        return $this->getClient()
            ->request(
                self::getAllEndpoint(),
                [
                    'jwt' => (string)$jwt,
                    'query' => ['page' => $page],
                ]
            );
    }

    /**
     * @param string $fullName
     * @param Token $jwt
     * @param int $page
     * @return Response
     * @throws ClientException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function allByFullName(string $fullName, Token $jwt, int $page = 1): Response
    {
        return $this->getClient()
            ->request(
                self::getAllEndpoint(),
                [
                    'jwt' => (string)$jwt,
                    'query' => [
                        'fullName' => $fullName,
                        'page' => $page
                    ],
                ]
            );
    }

    private static function getAllEndpoint(): Endpoint
    {
        $endpoint = new Endpoint('/data/users');
        $endpoint->setResponseValidationSchema(
            new JsonSchema(self::getPaginationSchemaFor(User::class))
        )
            ->setResponseFormatter(
                static function (array $response): array {
                    $response['results'] = array_map(
                        static fn(array $user): User => ModelFactory::createUser($user),
                        $response['results']
                    );
                    return $response;
                }
            );
        return $endpoint;
    }

    /**
     * @param string $uid
     * @param Token $jwt
     *
     * @return Response
     * @throws ClientException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function get(
        string $uid,
        Token $jwt
    ): Response
    {
        $endpoint = new Endpoint('/data/users/%s');
        $endpoint->setResponseValidationSchema(new JsonSchema(User::getSchema()))
            ->setResponseFormatter(static fn(array $response): User => ModelFactory::createUser($response));

        return $this->getClient()
            ->request(
                $endpoint,
                [
                    'jwt' => (string)$jwt,
                    'uriParameters' => [$uid],
                ]
            );
    }

    /**
     * @param string $uid
     * @param Token $jwt
     *
     * @return Response
     * @throws ClientException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function exists(
        string $uid,
        Token $jwt
    ): Response
    {
        $endpoint = new Endpoint(
            '/data/users/%s',
            'HEAD'
        );

        return $this->getClient()
            ->request(
                $endpoint,
                [
                    'jwt' => (string)$jwt,
                    'uriParameters' => [$uid],
                ]
            );
    }

    /**
     * @param string $email
     * @param string $googleId
     * @param Token $jwt
     *
     * @return Response
     * @throws ClientException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function getByEmailAndGoogleId(
        string $email,
        string $googleId,
        Token $jwt
    ): Response
    {
        $endpoint = new Endpoint('/data/users');
        $endpoint->setResponseValidationSchema(new JsonSchema(User::getSchema()))
            ->setResponseFormatter(static fn(array $response): User => ModelFactory::createUser($response));

        return $this->getClient()
            ->request(
                $endpoint,
                [
                    'jwt' => (string)$jwt,
                    'query' => [
                        'email' => $email,
                        'googleId' => $googleId,
                    ],
                ]
            );
    }

    /**
     * @param User $user
     * @param Token $jwt
     *
     * @return Response
     * @throws ClientException
     * @throws NormalizerException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function create(
        User $user,
        Token $jwt
    ): Response
    {
        try {
            $leads = ModelHelper::getUids(
                $user->getLeads(),
                Post::class
            );
            $posts = ModelHelper::getUids(
                $user->getPosts(),
                Post::class
            );
            // @codeCoverageIgnoreStart
        } catch (InvalidArgumentException $e) {
            $this->getLogger()
                ->error($e);
            throw new DataServiceException(
                'Error while getting uids',
                DataServiceException::INVALID_MODEL,
                $e
            );
        }
        // @codeCoverageIgnoreEnd

        $endpoint = new Endpoint(
            '/data/users',
            'POST'
        );
        $endpoint->setResponseValidationSchema(new JsonSchema(User::getSchema()))
            ->setResponseFormatter(static fn(array $response): User => ModelFactory::createUser($response));

        $data = array_merge(
            Normalizer::getInstance()
                ->normalize(
                    $user,
                    [AbstractNormalizer::GROUPS => ['create']]
                ),
            [
                'leads' => $leads,
                'posts' => $posts,
            ]
        );

        return $this->getClient()
            ->request(
                $endpoint,
                [
                    'jwt' => (string)$jwt,
                    'json' => $data,
                ]
            );
    }

    /**
     * @param User $user
     * @param Token $jwt
     *
     * @return Response
     * @throws ClientException
     * @throws NormalizerException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function update(
        User $user,
        Token $jwt
    ): Response
    {
        if ($user->getUid() === null) {
            throw new DataServiceException(
                'User lacks an uid',
                DataServiceException::MISSING_USER_UID
            );
        }

        $endpoint = new Endpoint(
            '/data/users/%s',
            'PUT'
        );

        $data = Normalizer::getInstance()
            ->normalize(
                $user,
                [AbstractNormalizer::GROUPS => ['update']]
            );

        return $this->getClient()
            ->request(
                $endpoint,
                [
                    'jwt' => (string)$jwt,
                    'json' => $data,
                    'uriParameters' => [$user->getUid()],
                ]
            );
    }

    /**
     * @param User $user
     * @param Token $jwt
     *
     * @return Response
     * @throws ClientException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function delete(
        User $user,
        Token $jwt
    ): Response
    {
        if ($user->getUid() === null) {
            throw new DataServiceException(
                'User lacks an uid',
                DataServiceException::MISSING_USER_UID
            );
        }

        $endpoint = new Endpoint(
            '/data/users/%s',
            'DELETE'
        );

        return $this->getClient()
            ->request(
                $endpoint,
                [
                    'jwt' => (string)$jwt,
                    'uriParameters' => [$user->getUid()],
                ]
            );
    }
}
