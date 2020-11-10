<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Authentication\ClientApp;

use hunomina\DataValidator\Schema\Json\JsonSchema;
use Jalismrs\Stalactite\Client\AbstractService;
use Jalismrs\Stalactite\Client\Authentication\Model\ClientApp;
use Jalismrs\Stalactite\Client\Authentication\Model\ModelFactory;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\NormalizerException;
use Jalismrs\Stalactite\Client\Exception\Service\AuthenticationServiceException;
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
 * @package Jalismrs\Stalactite\Client\Authentication\ClientApp
 */
class Service extends AbstractService
{
    use PaginationMetadataTrait;

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
                    'jwt' => (string)$jwt,
                    'query' => ['page' => $page]
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
        return $this->getClient()
            ->request(
                self::getAllEndpoint(),
                [
                    'jwt' => (string)$jwt,
                    'query' => [
                        'name' => $name,
                        'page' => $page
                    ]
                ]
            );
    }

    private static function getAllEndpoint(): Endpoint
    {
        $endpoint = new Endpoint('/auth/clientApps');
        $endpoint
            ->setResponseValidationSchema(
                new JsonSchema(self::getPaginationSchemaFor(ClientApp::class))
            )
            ->setResponseFormatter(
                static function (array $response): array {
                    $response['results'] = array_map(
                        static fn(array $clientApp): ClientApp => ModelFactory::createClientApp($clientApp),
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
    public function get(
        string $uid,
        Token $jwt
    ): Response
    {
        $endpoint = new Endpoint('/auth/clientApps/%s');
        $endpoint
            ->setResponseValidationSchema(new JsonSchema(ClientApp::getSchema()))
            ->setResponseFormatter(
                static fn(array $response): ClientApp => ModelFactory::createClientApp($response)
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
     * @param ClientApp $clientApp
     * @param Token $jwt
     *
     * @return Response
     * @throws ClientException
     * @throws InvalidArgumentException
     * @throws NormalizerException
     */
    public function create(
        ClientApp $clientApp,
        Token $jwt
    ): Response
    {
        $schema = new JsonSchema(ClientApp::getSchema());

        $endpoint = new Endpoint(
            '/auth/clientApps',
            'POST'
        );
        $endpoint
            ->setResponseValidationSchema($schema)
            ->setResponseFormatter(
                static fn(array $response): ClientApp => ModelFactory::createClientApp($response)
            );

        $data = Normalizer::getInstance()
            ->normalize(
                $clientApp,
                [
                    AbstractNormalizer::GROUPS => ['create'],
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
     * @param ClientApp $clientApp
     * @param Token $jwt
     *
     * @return Response
     * @throws ClientException
     * @throws InvalidArgumentException
     * @throws NormalizerException
     */
    public function update(
        ClientApp $clientApp,
        Token $jwt
    ): Response
    {
        if ($clientApp->getUid() === null) {
            throw new AuthenticationServiceException(
                'ClientApp lacks a uid',
                AuthenticationServiceException::MISSING_CLIENT_APP_UID
            );
        }

        $endpoint = new Endpoint(
            '/auth/clientApps/%s',
            'PUT'
        );

        $data = Normalizer::getInstance()
            ->normalize(
                $clientApp,
                [
                    AbstractNormalizer::GROUPS => ['update'],
                ]
            );

        return $this
            ->getClient()
            ->request(
                $endpoint,
                [
                    'jwt' => (string)$jwt,
                    'json' => $data,
                    'uriParameters' => [$clientApp->getUid()],
                ]
            );
    }

    /**
     * @param ClientApp $clientApp
     * @param Token $jwt
     *
     * @return Response
     * @throws ClientException
     * @throws InvalidArgumentException
     */
    public function delete(
        ClientApp $clientApp,
        Token $jwt
    ): Response
    {
        if ($clientApp->getUid() === null) {
            throw new AuthenticationServiceException(
                'ClientApp lacks a uid',
                AuthenticationServiceException::MISSING_CLIENT_APP_UID
            );
        }

        $endpoint = new Endpoint(
            '/auth/clientApps/%s',
            'DELETE'
        );

        return $this->getClient()
            ->request(
                $endpoint,
                [
                    'jwt' => (string)$jwt,
                    'uriParameters' => [$clientApp->getUid()],
                ]
            );
    }
}
