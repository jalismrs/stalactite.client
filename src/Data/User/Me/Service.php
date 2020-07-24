<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Data\User\Me;

use hunomina\DataValidator\Schema\Json\JsonSchema;
use Jalismrs\Stalactite\Client\AbstractService;
use Jalismrs\Stalactite\Client\Data\Model\ModelFactory;
use Jalismrs\Stalactite\Client\Data\Model\User;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Util\Endpoint;
use Jalismrs\Stalactite\Client\Util\Response;
use Lcobucci\JWT\Token;
use Psr\SimpleCache\InvalidArgumentException;

class Service extends AbstractService
{
    private ?Lead\Service $serviceLead = null;
    private ?Post\Service $servicePost = null;
    private ?Access\Service $serviceAccess = null;
    private ?Relation\Service $serviceRelation = null;
    private ?Subordinate\Service $serviceSubordinate = null;

    public function leads(): Lead\Service
    {
        if ($this->serviceLead === null) {
            $this->serviceLead = new Lead\Service($this->getClient());
        }

        return $this->serviceLead;
    }

    public function posts(): Post\Service
    {
        if ($this->servicePost === null) {
            $this->servicePost = new Post\Service($this->getClient());
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

    /**
     * @param Token $jwt
     * @return Response
     * @throws ClientException
     * @throws InvalidArgumentException
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
}
