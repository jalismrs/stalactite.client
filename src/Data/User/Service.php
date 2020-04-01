<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Data\User;

use hunomina\DataValidator\Schema\Json\JsonSchema;
use InvalidArgumentException;
use Jalismrs\Stalactite\Client\AbstractService;
use Jalismrs\Stalactite\Client\Data\Model\ModelFactory;
use Jalismrs\Stalactite\Client\Data\Model\Post;
use Jalismrs\Stalactite\Client\Data\Model\User;
use Jalismrs\Stalactite\Client\Data\Schema;
use Jalismrs\Stalactite\Client\Data\User\Post\Service as PostService;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\SerializerException;
use Jalismrs\Stalactite\Client\Exception\Service\DataServiceException;
use Jalismrs\Stalactite\Client\Util\Endpoint;
use Jalismrs\Stalactite\Client\Util\ModelHelper;
use Jalismrs\Stalactite\Client\Util\Normalizer;
use Jalismrs\Stalactite\Client\Util\Response;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use function array_map;
use function array_merge;

/**
 * Service
 *
 * @package Jalismrs\Stalactite\Service\Data\User
 */
class Service extends AbstractService
{
    private ?Lead\Service $serviceLead = null;

    private ?Me\Service $serviceMe = null;

    private ?PostService $servicePost = null;

    /*
     * -------------------------------------------------------------------------
     * Clients -----------------------------------------------------------------
     * -------------------------------------------------------------------------
     */

    /**
     * leads
     *
     * @return Lead\Service
     */
    public function leads(): Lead\Service
    {
        if ($this->serviceLead === null) {
            $this->serviceLead = new Lead\Service($this->getClient());
        }

        return $this->serviceLead;
    }

    /**
     * me
     *
     * @return Me\Service
     */
    public function me(): Me\Service
    {
        if ($this->serviceMe === null) {
            $this->serviceMe = new Me\Service($this->getClient());
        }

        return $this->serviceMe;
    }

    /**
     * posts
     *
     * @return PostService
     */
    public function posts(): PostService
    {
        if ($this->servicePost === null) {
            $this->servicePost = new PostService($this->getClient());
        }

        return $this->servicePost;
    }

    /*
     * -------------------------------------------------------------------------
     * API ---------------------------------------------------------------------
     * -------------------------------------------------------------------------
     */

    /**
     * @param string $jwt
     * @return Response
     * @throws ClientException
     */
    public function getAllUsers(string $jwt): Response
    {
        $endpoint = new Endpoint('/data/users');
        $endpoint->setResponseValidationSchema(new JsonSchema(Schema::USER, JsonSchema::LIST_TYPE))
            ->setResponseFormatter(static function (array $response): array {
                return array_map(static fn(array $user): User => ModelFactory::createUser($user), $response);
            });

        return $this->getClient()->request($endpoint, [
            'jwt' => $jwt
        ]);
    }

    /**
     * @param string $uid
     * @param string $jwt
     * @return Response
     * @throws ClientException
     */
    public function getUser(string $uid, string $jwt): Response
    {
        $endpoint = new Endpoint('/data/users/%s');
        $endpoint->setResponseValidationSchema(new JsonSchema(Schema::USER))
            ->setResponseFormatter(static fn(array $response): User => ModelFactory::createUser($response));

        return $this->getClient()->request($endpoint, [
            'jwt' => $jwt,
            'uriParameters' => [$uid]
        ]);
    }

    /**
     * @param string $uid
     * @param string $jwt
     * @return Response
     * @throws ClientException
     */
    public function getUserSubordinates(string $uid, string $jwt): Response
    {
        $endpoint = new Endpoint('/data/users/%s/subordinates');
        $endpoint->setResponseValidationSchema(new JsonSchema(Schema::USER, JsonSchema::LIST_TYPE))
            ->setResponseFormatter(static function (array $response): array {
                return array_map(static fn(array $user): User => ModelFactory::createUser($user), $response);
            });

        return $this->getClient()->request($endpoint, [
            'jwt' => $jwt,
            'uriParameters' => [$uid]
        ]);
    }

    /**
     * @param User $user
     * @param string $jwt
     * @return Response
     * @throws ClientException
     * @throws SerializerException
     */
    public function createUser(User $user, string $jwt): Response
    {
        try {
            $leads = ModelHelper::getUids($user->getLeads(), Post::class);
            $posts = ModelHelper::getUids($user->getPosts(), Post::class);
        } catch (InvalidArgumentException $e) {
            $this->getLogger()->error($e);
            throw new DataServiceException('Error while getting uids', DataServiceException::INVALID_MODEL, $e);
        }

        $endpoint = new Endpoint('/data/users', 'POST');
        $endpoint->setResponseValidationSchema(new JsonSchema(Schema::USER))
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

        return $this->getClient()->request($endpoint, [
            'jwt' => $jwt,
            'json' => $data
        ]);
    }

    /**
     * @param User $user
     * @param string $jwt
     * @return Response
     * @throws ClientException
     * @throws SerializerException
     */
    public function updateUser(User $user, string $jwt): Response
    {
        if ($user->getUid() === null) {
            throw new DataServiceException('User lacks an uid', DataServiceException::MISSING_USER_UID);
        }

        $endpoint = new Endpoint('/data/users/%s', 'PUT');

        $data = Normalizer::getInstance()->normalize(
            $user,
            [AbstractNormalizer::GROUPS => ['update']]
        );

        return $this->getClient()->request($endpoint, [
            'jwt' => $jwt,
            'json' => $data,
            'uriParameters' => [$user->getUid()]
        ]);
    }

    /**
     * @param User $user
     * @param string $jwt
     * @return Response
     * @throws ClientException
     */
    public function deleteUser(User $user, string $jwt): Response
    {
        if ($user->getUid() === null) {
            throw new DataServiceException('User lacks an uid', DataServiceException::MISSING_USER_UID);
        }

        $endpoint = new Endpoint('/data/users/%s', 'DELETE');

        return $this->getClient()->request($endpoint, [
            'jwt' => $jwt,
            'uriParameters' => [$user->getUid()]
        ]);
    }
}
