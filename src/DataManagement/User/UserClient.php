<?php

namespace jalismrs\Stalactite\Client\DataManagement\User;

use hunomina\Validator\Json\Exception\InvalidDataException;
use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Rule\JsonRule;
use hunomina\Validator\Json\Schema\JsonSchema;
use jalismrs\Stalactite\Client\AbstractClient;
use jalismrs\Stalactite\Client\ClientException;
use jalismrs\Stalactite\Client\DataManagement\Client;
use jalismrs\Stalactite\Client\DataManagement\Model\User;
use jalismrs\Stalactite\Client\DataManagement\Schema;

class UserClient extends AbstractClient
{
    public const API_URL_PREFIX = Client::API_URL_PREFIX . '/users';

    /** @var MeClient $meClient */
    private $meClient;

    /** @var PostClient $postClient */
    private $postClient;

    /** @var LeadClient $leadClient */
    private $leadClient;

    /** @var CertificationClient $certificationClient */
    private $certificationClient;

    /** @var PhoneClient $phoneClient */
    private $phoneClient;

    /**
     * @return MeClient
     */
    public function me(): MeClient
    {
        if (!($this->meClient instanceof MeClient)) {
            $this->meClient = new MeClient($this->apiHost, $this->userAgent);
            $this->meClient->setHttpClient($this->getHttpClient());
        }

        return $this->meClient;
    }

    /**
     * @return PostClient
     */
    public function posts(): PostClient
    {
        if (!($this->postClient instanceof PostClient)) {
            $this->postClient = new PostClient($this->apiHost, $this->userAgent);
            $this->postClient->setHttpClient($this->getHttpClient());
        }

        return $this->postClient;
    }

    /**
     * @return LeadClient
     */
    public function leads(): LeadClient
    {
        if (!($this->leadClient instanceof LeadClient)) {
            $this->leadClient = new LeadClient($this->apiHost, $this->userAgent);
            $this->leadClient->setHttpClient($this->getHttpClient());
        }

        return $this->leadClient;
    }

    /**
     * @return CertificationClient
     */
    public function certifications(): CertificationClient
    {
        if (!($this->certificationClient instanceof CertificationClient)) {
            $this->certificationClient = new CertificationClient($this->apiHost, $this->userAgent);
            $this->certificationClient->setHttpClient($this->getHttpClient());
        }

        return $this->certificationClient;
    }

    /**
     * @return PhoneClient
     */
    public function phones(): PhoneClient
    {
        if (!($this->phoneClient instanceof PhoneClient)) {
            $this->phoneClient = new PhoneClient($this->apiHost, $this->userAgent);
            $this->phoneClient->setHttpClient($this->getHttpClient());
        }

        return $this->phoneClient;
    }

    /**
     * @param string $jwt
     * @return array
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     * @throws ClientException
     */
    public function getAll(string $jwt): array
    {
        $schema = new JsonSchema();
        $schema->setSchema([
            'success' => ['type' => JsonRule::BOOLEAN_TYPE],
            'error' => ['type' => JsonRule::STRING_TYPE, 'null' => true],
            'users' => ['type' => JsonRule::LIST_TYPE, 'schema' => Schema::MINIMAL_USER]
        ]);

        return $this->request('GET', $this->apiHost . self::API_URL_PREFIX, [
            'headers' => ['X-API-TOKEN' => $jwt]
        ], $schema);
    }

    /**
     * @param User $user
     * @param string $jwt
     * @return array
     * @throws ClientException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function get(User $user, string $jwt): array
    {
        $schema = new JsonSchema();
        $schema->setSchema([
            'success' => ['type' => JsonRule::BOOLEAN_TYPE],
            'error' => ['type' => JsonRule::STRING_TYPE, 'null' => true],
            'user' => ['type' => JsonRule::OBJECT_TYPE, 'null' => true, 'schema' => Schema::USER]
        ]);

        return $this->request('GET', $this->apiHost . self::API_URL_PREFIX . '/' . $user->getUid(), [
            'headers' => ['X-API-TOKEN' => $jwt]
        ], $schema);
    }

    /**
     * @param User $user
     * @param string $jwt
     * @return array
     * @throws ClientException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function create(User $user, string $jwt): array
    {
        $body = $user->asMinimalArray();

        foreach ($user->getPosts() as $post) {
            $body['posts'] = $post->getUid();
        }

        foreach ($user->getLeads() as $lead) {
            $body['leads'] = $lead->getUid();
        }

        $schema = new JsonSchema();
        $schema->setSchema([
            'success' => ['type' => JsonRule::BOOLEAN_TYPE],
            'error' => ['type' => JsonRule::STRING_TYPE, 'null' => true],
            'user' => ['type' => JsonRule::OBJECT_TYPE, 'schema' => Schema::USER]
        ]);

        return $this->request('POST', $this->apiHost . self::API_URL_PREFIX, [
            'headers' => ['X-API-TOKEN' => $jwt],
            'json' => $body
        ], $schema);
    }

    /**
     * @param User $user
     * @param string $jwt
     * @return array
     * @throws ClientException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function update(User $user, string $jwt): array
    {
        $body = $user->asMinimalArray();
        unset($body['googleId'], $body['uid']);

        $schema = new JsonSchema();
        $schema->setSchema([
            'success' => ['type' => JsonRule::BOOLEAN_TYPE],
            'error' => ['type' => JsonRule::STRING_TYPE, 'null' => true]
        ]);

        return $this->request('PUT', $this->apiHost . self::API_URL_PREFIX . '/' . $user->getUid(), [
            'headers' => ['X-API-TOKEN' => $jwt],
            'json' => $body
        ], $schema);
    }

    /**
     * @param User $user
     * @param string $jwt
     * @return array
     * @throws ClientException
     * @throws InvalidDataException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function delete(User $user, string $jwt): array
    {
        $schema = new JsonSchema();
        $schema->setSchema([
            'success' => ['type' => JsonRule::BOOLEAN_TYPE],
            'error' => ['type' => JsonRule::STRING_TYPE, 'null' => true]
        ]);

        return $this->request('DELETE', $this->apiHost . self::API_URL_PREFIX . '/' . $user->getUid(), [
            'headers' => ['X-API-TOKEN' => $jwt]
        ], $schema);
    }
}