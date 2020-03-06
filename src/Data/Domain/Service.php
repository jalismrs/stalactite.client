<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Data\Domain;

use hunomina\DataValidator\Schema\Json\JsonSchema;
use Jalismrs\Stalactite\Client\AbstractService;
use Jalismrs\Stalactite\Client\Data\Model\Domain;
use Jalismrs\Stalactite\Client\Data\Model\ModelFactory;
use Jalismrs\Stalactite\Client\Data\Schema;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\SerializerException;
use Jalismrs\Stalactite\Client\Exception\Service\DataServiceException;
use Jalismrs\Stalactite\Client\Util\Endpoint;
use Jalismrs\Stalactite\Client\Util\Response;
use Jalismrs\Stalactite\Client\Util\Serializer;
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
     * @param string $jwt
     * @return Response
     * @throws ClientException
     */
    public function getAllDomains(string $jwt): Response
    {
        $endpoint = new Endpoint('/data/domains');
        $endpoint->setResponseValidationSchema(new JsonSchema(Schema::DOMAIN, JsonSchema::LIST_TYPE))
            ->setResponseFormatter(static function (array $response): array {
                return array_map(static function (array $domain): Domain {
                    return ModelFactory::createDomain($domain);
                }, $response);
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
    public function getDomain(string $uid, string $jwt): Response
    {
        $endpoint = new Endpoint('/data/domains/%s');
        $endpoint->setResponseValidationSchema(new JsonSchema(Schema::DOMAIN))
            ->setResponseFormatter(static function (array $response): Domain {
                return ModelFactory::createDomain($response);
            });

        return $this->getClient()->request($endpoint, [
            'jwt' => $jwt,
            'uriParameters' => [$uid]
        ]);
    }

    /**
     * @param string $name
     * @param string $jwt
     * @return Response
     * @throws ClientException
     */
    public function getByName(string $name, string $jwt): Response
    {
        $endpoint = new Endpoint('/data/domains');
        $endpoint->setResponseValidationSchema(new JsonSchema(Schema::DOMAIN, JsonSchema::LIST_TYPE))
            ->setResponseFormatter(static function (array $response): array {
                return array_map(static function (array $domain): Domain {
                    return ModelFactory::createDomain($domain);
                }, $response);
            });

        return $this->getClient()->request($endpoint, [
            'jwt' => $jwt,
            'query' => ['name' => $name]
        ]);
    }

    /**
     * @param Domain $domain
     * @param string $jwt
     * @return Response
     * @throws ClientException
     * @throws SerializerException
     */
    public function createDomain(Domain $domain, string $jwt): Response
    {
        $endpoint = new Endpoint('/data/domains', 'POST');
        $endpoint->setResponseValidationSchema(new JsonSchema(Schema::DOMAIN))
            ->setResponseFormatter(static function (array $response): Domain {
                return ModelFactory::createDomain($response);
            });

        $data = Serializer::getInstance()->normalize($domain, [
            AbstractNormalizer::GROUPS => ['create']
        ]);

        return $this->getClient()->request($endpoint, [
            'jwt' => $jwt,
            'json' => $data
        ]);
    }

    /**
     * @param Domain $domain
     * @param string $jwt
     * @return Response
     * @throws ClientException
     * @throws SerializerException
     */
    public function updateDomain(Domain $domain, string $jwt): Response
    {
        if ($domain->getUid() === null) {
            throw new DataServiceException('Domain lacks an uid', DataServiceException::MISSING_DOMAIN_UID);
        }

        $endpoint = new Endpoint('/data/domains/%s', 'PUT');

        $data = Serializer::getInstance()->normalize($domain, [
            AbstractNormalizer::GROUPS => ['update']
        ]);

        return $this->getClient()->request($endpoint, [
            'jwt' => $jwt,
            'json' => $data,
            'uriParameters' => [$domain->getUid()]
        ]);
    }

    /**
     * @param Domain $domain
     * @param string $jwt
     * @return Response
     * @throws ClientException
     */
    public function deleteDomain(Domain $domain, string $jwt): Response
    {
        if ($domain->getUid() === null) {
            throw new DataServiceException('Domain lacks an uid', DataServiceException::MISSING_DOMAIN_UID);
        }

        $endpoint = new Endpoint('/data/domains/%s', 'DELETE');

        return $this->getClient()->request($endpoint, [
            'jwt' => $jwt,
            'uriParameters' => [$domain->getUid()]
        ]);
    }
}
