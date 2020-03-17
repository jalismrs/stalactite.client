<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Data\User\Lead;

use hunomina\DataValidator\Schema\Json\JsonSchema;
use InvalidArgumentException;
use Jalismrs\Stalactite\Client\AbstractService;
use Jalismrs\Stalactite\Client\Data\Model\ModelFactory;
use Jalismrs\Stalactite\Client\Data\Model\Post;
use Jalismrs\Stalactite\Client\Data\Model\User;
use Jalismrs\Stalactite\Client\Data\Schema;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\Service\DataServiceException;
use Jalismrs\Stalactite\Client\Util\Endpoint;
use Jalismrs\Stalactite\Client\Util\ModelHelper;
use Jalismrs\Stalactite\Client\Util\Response;
use function array_map;

/**
 * Class Service
 * @package Jalismrs\Stalactite\Client\Data\User\Lead
 */
class Service extends AbstractService
{
    /**
     * @param User $user
     * @param string $jwt
     * @return Response
     * @throws ClientException
     */
    public function getAllLeads(User $user, string $jwt): Response
    {
        if ($user->getUid() === null) {
            throw new DataServiceException('User lacks an uid', DataServiceException::MISSING_USER_UID);
        }

        $endpoint = new Endpoint('/data/users/%s/leads');
        $endpoint->setResponseValidationSchema(new JsonSchema(Schema::POST, JsonSchema::LIST_TYPE))
            ->setResponseFormatter(static function (array $response): array {
                return array_map(static fn(array $post): Post => ModelFactory::createPost($post), $response);
            });

        return $this->getClient()->request($endpoint, [
            'jwt' => $jwt,
            'uriParameters' => [$user->getUid()]
        ]);
    }

    /**
     * @param User $user
     * @param array $leads
     * @param string $jwt
     * @return Response
     * @throws ClientException
     */
    public function addLeads(User $user, array $leads, string $jwt): Response
    {
        if ($user->getUid() === null) {
            throw new DataServiceException('User lacks an uid', DataServiceException::MISSING_USER_UID);
        }

        try {
            $leads = ModelHelper::getUids($leads, Post::class);
        } catch (InvalidArgumentException $e) {
            $this->getLogger()->error($e);
            throw new DataServiceException('Error while getting leads uids', DataServiceException::INVALID_MODEL, $e);
        }

        $endpoint = new Endpoint('/data/users/%s/leads', 'POST');

        return $this->getClient()->request($endpoint, [
            'jwt' => $jwt,
            'json' => ['leads' => $leads],
            'uriParameters' => [$user->getUid()]
        ]);
    }

    /**
     * @param User $user
     * @param array $leads
     * @param string $jwt
     * @return Response
     * @throws ClientException
     */
    public function removeLeads(User $user, array $leads, string $jwt): Response
    {
        if ($user->getUid() === null) {
            throw new DataServiceException('User lacks an uid', DataServiceException::MISSING_USER_UID);
        }

        try {
            $leads = ModelHelper::getUids($leads, Post::class);
        } catch (InvalidArgumentException $e) {
            $this->getLogger()->error($e);
            throw new DataServiceException('Error while getting leads uids', DataServiceException::INVALID_MODEL, $e);
        }

        $endpoint = new Endpoint('/data/users/%s/leads', 'DELETE');

        return $this->getClient()->request($endpoint, [
            'jwt' => $jwt,
            'json' => ['leads' => $leads],
            'uriParameters' => [$user->getUid()]
        ]);
    }
}
