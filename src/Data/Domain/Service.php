<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Data\Domain;

use hunomina\DataValidator\Schema\Json\JsonSchema;
use Jalismrs\Stalactite\Client\AbstractService;
use Jalismrs\Stalactite\Client\Data\Model\Domain;
use Jalismrs\Stalactite\Client\Data\Model\ModelFactory;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\NormalizerException;
use Jalismrs\Stalactite\Client\Exception\Service\DataServiceException;
use Jalismrs\Stalactite\Client\PaginationMetadataTrait;
use Jalismrs\Stalactite\Client\Util\Endpoint;
use Jalismrs\Stalactite\Client\Util\Normalizer;
use Jalismrs\Stalactite\Client\Util\Response;
use Lcobucci\JWT\Token;
use Psr\SimpleCache\InvalidArgumentException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use function array_map;

/**
 * Class Service
 *
 * @package Jalismrs\Stalactite\Client\Data\Domain
 */
class Service extends AbstractService
{
    use PaginationMetadataTrait;

    private ?Relation\Service $serviceRelation = null;

    public function relations(): Relation\Service
    {
        if ($this->serviceRelation === null) {
            $this->serviceRelation = new Relation\Service($this->getClient());
        }

        return $this->serviceRelation;
    }

    /**
     * @param Token $jwt
     * @param int $page
     * @return Response
     * @throws ClientException
     * @throws InvalidArgumentException
     */
    public function all(Token $jwt, int $page = 1): Response
    {
        return $this->getClient()
            ->request(
                self::getAllEndpoint(),
                [
                    'jwt' => $jwt->toString(),
                    'query' => ['page' => $page],
                ]
            );
    }

    /**
     * @param string $name
     * @param Token $jwt
     * @param int $page
     * @return Response
     * @throws ClientException
     * @throws InvalidArgumentException
     */
    public function allByName(string $name, Token $jwt, int $page = 1): Response
    {
        return $this->getClient()->request(
            self::getAllEndpoint(),
            [
                'jwt' => $jwt->toString(),
                'query' => [
                    'name' => $name,
                    'page' => $page
                ],
            ]
        );
    }

    private static function getAllEndpoint(): Endpoint
    {
        $endpoint = new Endpoint('/data/domains');
        $endpoint->setResponseValidationSchema(
            new JsonSchema(self::getPaginationSchemaFor(Domain::class))
        )
            ->setResponseFormatter(
                static function (array $response): array {
                    $response['results'] = array_map(
                        static fn(array $domain): Domain => ModelFactory::createDomain($domain),
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
     * @throws InvalidArgumentException
     */
    public function get(string $uid, Token $jwt): Response
    {
        $endpoint = new Endpoint('/data/domains/%s');
        $endpoint->setResponseValidationSchema(new JsonSchema(Domain::getSchema()))
            ->setResponseFormatter(static fn(array $response): Domain => ModelFactory::createDomain($response));

        return $this->getClient()->request(
            $endpoint,
            [
                'jwt' => $jwt->toString(),
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
     * @throws InvalidArgumentException
     */
    public function exists(
        string $uid,
        Token $jwt
    ): Response
    {
        $endpoint = new Endpoint(
            '/data/domains/%s',
            'HEAD'
        );

        return $this->getClient()
            ->request(
                $endpoint,
                [
                    'jwt' => $jwt->toString(),
                    'uriParameters' => [$uid],
                ]
            );
    }

    /**
     * @param string $name
     * @param string $apiKey
     * @param Token $jwt
     *
     * @return Response
     * @throws ClientException
     * @throws InvalidArgumentException
     */
    public function getByNameAndApiKey(
        string $name,
        string $apiKey,
        Token $jwt
    ): Response
    {
        $endpoint = new Endpoint('/data/domains');
        $endpoint->setResponseValidationSchema(new JsonSchema(Domain::getSchema()))
            ->setResponseFormatter(static fn(array $response): Domain => ModelFactory::createDomain($response));

        return $this->getClient()
            ->request(
                $endpoint,
                [
                    'jwt' => $jwt->toString(),
                    'query' => [
                        'name' => $name,
                        'apiKey' => $apiKey,
                    ],
                ]
            );
    }

    /**
     * @param Domain $domain
     * @param Token $jwt
     *
     * @return Response
     * @throws ClientException
     * @throws NormalizerException
     * @throws InvalidArgumentException
     */
    public function create(
        Domain $domain,
        Token $jwt
    ): Response
    {
        $endpoint = new Endpoint(
            '/data/domains',
            'POST'
        );
        $endpoint->setResponseValidationSchema(new JsonSchema(Domain::getSchema()))
            ->setResponseFormatter(static fn(array $response): Domain => ModelFactory::createDomain($response));

        $data = Normalizer::getInstance()
            ->normalize(
                $domain,
                [
                    AbstractNormalizer::GROUPS => ['create'],
                ]
            );

        return $this->getClient()
            ->request(
                $endpoint,
                [
                    'jwt' => $jwt->toString(),
                    'json' => $data,
                ]
            );
    }

    /**
     * @param Domain $domain
     * @param Token $jwt
     *
     * @return Response
     * @throws ClientException
     * @throws NormalizerException
     * @throws InvalidArgumentException
     */
    public function update(
        Domain $domain,
        Token $jwt
    ): Response
    {
        if ($domain->getUid() === null) {
            throw new DataServiceException(
                'Domain lacks an uid',
                DataServiceException::MISSING_DOMAIN_UID
            );
        }

        $endpoint = new Endpoint(
            '/data/domains/%s',
            'PUT'
        );

        $data = Normalizer::getInstance()
            ->normalize(
                $domain,
                [
                    AbstractNormalizer::GROUPS => ['update'],
                ]
            );

        return $this->getClient()
            ->request(
                $endpoint,
                [
                    'jwt' => $jwt->toString(),
                    'json' => $data,
                    'uriParameters' => [$domain->getUid()],
                ]
            );
    }

    /**
     * @param Domain $domain
     * @param Token $jwt
     *
     * @return Response
     * @throws ClientException
     * @throws InvalidArgumentException
     */
    public function delete(
        Domain $domain,
        Token $jwt
    ): Response
    {
        if ($domain->getUid() === null) {
            throw new DataServiceException(
                'Domain lacks an uid',
                DataServiceException::MISSING_DOMAIN_UID
            );
        }

        $endpoint = new Endpoint(
            '/data/domains/%s',
            'DELETE'
        );

        return $this->getClient()
            ->request(
                $endpoint,
                [
                    'jwt' => $jwt->toString(),
                    'uriParameters' => [$domain->getUid()],
                ]
            );
    }
}
