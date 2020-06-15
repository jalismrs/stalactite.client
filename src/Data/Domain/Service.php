<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Data\Domain;

use hunomina\DataValidator\Schema\Json\JsonSchema;
use Jalismrs\Stalactite\Client\AbstractService;
use Jalismrs\Stalactite\Client\Data\Model\Domain;
use Jalismrs\Stalactite\Client\Data\Model\ModelFactory;
use Jalismrs\Stalactite\Client\Data\Schema;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\NormalizerException;
use Jalismrs\Stalactite\Client\Exception\Service\DataServiceException;
use Jalismrs\Stalactite\Client\Util\Endpoint;
use Jalismrs\Stalactite\Client\Util\Normalizer;
use Jalismrs\Stalactite\Client\Util\Response;
use Lcobucci\JWT\Token;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use function array_map;

/**
 * Service
 *
 * @package Jalismrs\Stalactite\Service\Data\DOmain
 */
class Service extends
    AbstractService
{
    /**
     * @param Token $jwt
     * @return Response
     * @throws ClientException
     */
    public function getAllDomains(Token $jwt): Response
    {
        $endpoint = new Endpoint('/data/domains');
        $endpoint->setResponseValidationSchema(new JsonSchema(Schema::DOMAIN, JsonSchema::LIST_TYPE))
            ->setResponseFormatter(static function (array $response): array {
                return array_map(
                    static fn(array $domain): Domain => ModelFactory::createDomain($domain),
                    $response
                );
            });

        return $this->getClient()->request($endpoint, [
            'jwt' => (string)$jwt
        ]);
    }

    /**
     * @param string $uid
     * @param Token $jwt
     * @return Response
     * @throws ClientException
     */
    public function getDomain(string $uid, Token $jwt): Response
    {
        $endpoint = new Endpoint('/data/domains/%s');
        $endpoint->setResponseValidationSchema(new JsonSchema(Schema::DOMAIN))
            ->setResponseFormatter(static fn(array $response): Domain => ModelFactory::createDomain($response));

        return $this->getClient()->request($endpoint, [
            'jwt' => (string)$jwt,
            'uriParameters' => [$uid]
        ]);
    }

    /**
     * @param string $name
     * @param string $apiKey
     * @param Token $jwt
     * @return Response
     * @throws ClientException
     */
    public function getByNameAndApiKey(string $name, string $apiKey, Token $jwt): Response
    {
        $endpoint = new Endpoint('/data/domains');
        $endpoint->setResponseValidationSchema(new JsonSchema(Schema::DOMAIN))
            ->setResponseFormatter(static fn(array $response): Domain => ModelFactory::createDomain($response));

        return $this->getClient()->request($endpoint, [
            'jwt' => (string)$jwt,
            'query' => [
                'name' => $name,
                'apiKey' => $apiKey
            ]
        ]);
    }

    /**
     * @param string $name
     * @param Token $jwt
     * @return Response
     * @throws ClientException
     */
    public function getByName(string $name, Token $jwt): Response
    {
        $endpoint = new Endpoint('/data/domains');
        $endpoint->setResponseValidationSchema(new JsonSchema(Schema::DOMAIN))
            ->setResponseFormatter(static fn(array $response): Domain => ModelFactory::createDomain($response));

        return $this->getClient()->request($endpoint, [
            'jwt' => (string)$jwt,
            'query' => ['name' => $name]
        ]);
    }

    /**
     * @param Domain $domain
     * @param Token $jwt
     * @return Response
     * @throws ClientException
     * @throws NormalizerException
     */
    public function createDomain(Domain $domain, Token $jwt): Response
    {
        $endpoint = new Endpoint('/data/domains', 'POST');
        $endpoint->setResponseValidationSchema(new JsonSchema(Schema::DOMAIN))
            ->setResponseFormatter(static fn(array $response): Domain => ModelFactory::createDomain($response));

        $data = Normalizer::getInstance()->normalize($domain, [
            AbstractNormalizer::GROUPS => ['create']
        ]);

        return $this->getClient()->request($endpoint, [
            'jwt' => (string)$jwt,
            'json' => $data
        ]);
    }

    /**
     * @param Domain $domain
     * @param Token $jwt
     * @return Response
     * @throws ClientException
     * @throws NormalizerException
     */
    public function updateDomain(Domain $domain, Token $jwt): Response
    {
        if ($domain->getUid() === null) {
            throw new DataServiceException('Domain lacks an uid', DataServiceException::MISSING_DOMAIN_UID);
        }

        $endpoint = new Endpoint('/data/domains/%s', 'PUT');

        $data = Normalizer::getInstance()->normalize($domain, [
            AbstractNormalizer::GROUPS => ['update']
        ]);

        return $this->getClient()->request($endpoint, [
            'jwt' => (string)$jwt,
            'json' => $data,
            'uriParameters' => [$domain->getUid()]
        ]);
    }

    /**
     * @param Domain $domain
     * @param Token $jwt
     * @return Response
     * @throws ClientException
     */
    public function deleteDomain(Domain $domain, Token $jwt): Response
    {
        if ($domain->getUid() === null) {
            throw new DataServiceException('Domain lacks an uid', DataServiceException::MISSING_DOMAIN_UID);
        }

        $endpoint = new Endpoint('/data/domains/%s', 'DELETE');

        return $this->getClient()->request($endpoint, [
            'jwt' => (string)$jwt,
            'uriParameters' => [$domain->getUid()]
        ]);
    }
}
