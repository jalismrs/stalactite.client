<?php
declare(strict_types = 1);

namespace jalismrs\Stalactite\Client\DataManagement\User;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Rule\JsonRule;
use hunomina\Validator\Json\Schema\JsonSchema;
use jalismrs\Stalactite\Client\AbstractClient;
use jalismrs\Stalactite\Client\ClientException;
use jalismrs\Stalactite\Client\DataManagement\Client;
use jalismrs\Stalactite\Client\DataManagement\Model\ModelFactory;
use jalismrs\Stalactite\Client\DataManagement\Model\User;
use jalismrs\Stalactite\Client\DataManagement\Schema;
use jalismrs\Stalactite\Client\Response;

class UserClient extends AbstractClient
{
    public const API_URL_PREFIX = Client::API_URL_PREFIX . '/users';

    /** @var MeClient $meClient */
    private $meClient;

    /** @var PostClient $postClient */
    private $postClient;

    /** @var LeadClient $leadClient */
    private $leadClient;

    /** @var CertificationGraduationClient $certificationClient */
    private $certificationClient;

    /** @var PhoneLineClient $phoneClient */
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
     * @return CertificationGraduationClient
     */
    public function certifications(): CertificationGraduationClient
    {
        if (!($this->certificationClient instanceof CertificationGraduationClient)) {
            $this->certificationClient = new CertificationGraduationClient($this->apiHost, $this->userAgent);
            $this->certificationClient->setHttpClient($this->getHttpClient());
        }

        return $this->certificationClient;
    }

    /**
     * @return PhoneLineClient
     */
    public function phones(): PhoneLineClient
    {
        if (!($this->phoneClient instanceof PhoneLineClient)) {
            $this->phoneClient = new PhoneLineClient($this->apiHost, $this->userAgent);
            $this->phoneClient->setHttpClient($this->getHttpClient());
        }

        return $this->phoneClient;
    }

    /**
     * @param string $jwt
     * @return Response
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function getAll(string $jwt): Response
    {
        $schema = new JsonSchema();
        $schema->setSchema([
            'success' => ['type' => JsonRule::BOOLEAN_TYPE],
            'error' => ['type' => JsonRule::STRING_TYPE, 'null' => true],
            'users' => ['type' => JsonRule::LIST_TYPE, 'schema' => Schema::MINIMAL_USER]
        ]);

        $r = $this->request('GET', $this->apiHost . self::API_URL_PREFIX, [
            'headers' => ['X-API-TOKEN' => $jwt]
        ], $schema);

        $users = [];
        foreach ($r['users'] as $user) {
            $users[] = ModelFactory::createUser($user);
        }

        $response = new Response();
        $response->setSuccess($r['success'])->setError($r['error'])->setData([
            'users' => $users
        ]);

        return $response;
    }

    /**
     * @param string $uid
     * @param string $jwt
     * @return Response
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function get(string $uid, string $jwt): Response
    {
        $schema = new JsonSchema();
        $schema->setSchema([
            'success' => ['type' => JsonRule::BOOLEAN_TYPE],
            'error' => ['type' => JsonRule::STRING_TYPE, 'null' => true],
            'user' => ['type' => JsonRule::OBJECT_TYPE, 'null' => true, 'schema' => Schema::USER]
        ]);

        $r = $this->request('GET', $this->apiHost . self::API_URL_PREFIX . '/' . $uid, [
            'headers' => ['X-API-TOKEN' => $jwt]
        ], $schema);

        $response = new Response();
        $response->setSuccess($r['success'])->setError($r['error'])->setData([
            'user' => $r['user'] ? ModelFactory::createUser($r['user']) : null
        ]);

        return $response;
    }

    /**
     * @param string $email
     * @param string $googleId
     * @param string $jwt
     * @return Response
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function getByEmailAndGoogleId(string $email, string $googleId, string $jwt): Response
    {
        $schema = new JsonSchema();
        $schema->setSchema([
            'success' => ['type' => JsonRule::BOOLEAN_TYPE],
            'error' => ['type' => JsonRule::STRING_TYPE, 'null' => true],
            'user' => ['type' => JsonRule::OBJECT_TYPE, 'null' => true, 'schema' => Schema::USER]
        ]);

        $r = $this->request('GET', $this->apiHost . self::API_URL_PREFIX, [
            'headers' => ['X-API-TOKEN' => $jwt],
            'query' => [
                'email' => $email,
                'googleId' => $googleId
            ]
        ], $schema);

        $response = new Response();
        $response->setSuccess($r['success'])->setError($r['error'])->setData([
            'user' => $r['user'] ? ModelFactory::createUser($r['user']) : null
        ]);

        return $response;
    }

    /**
     * @param User $user
     * @param string $jwt
     * @return Response
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function create(User $user, string $jwt): Response
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
            'user' => ['type' => JsonRule::OBJECT_TYPE, 'null' => true, 'schema' => Schema::USER]
        ]);

        $r = $this->request('POST', $this->apiHost . self::API_URL_PREFIX, [
            'headers' => ['X-API-TOKEN' => $jwt],
            'json' => $body
        ], $schema);

        $response = new Response();
        $response->setSuccess($r['success'])->setError($r['error'])->setData([
            'user' => $r['user'] ? ModelFactory::createUser($r['user']) : null
        ]);

        return $response;
    }

    /**
     * @param User $user
     * @param string $jwt
     * @return Response
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function update(User $user, string $jwt): Response
    {
        $body = $user->asMinimalArray();
        unset($body['googleId'], $body['uid']);

        $schema = new JsonSchema();
        $schema->setSchema([
            'success' => ['type' => JsonRule::BOOLEAN_TYPE],
            'error' => ['type' => JsonRule::STRING_TYPE, 'null' => true]
        ]);

        $r = $this->request('PUT', $this->apiHost . self::API_URL_PREFIX . '/' . $user->getUid(), [
            'headers' => ['X-API-TOKEN' => $jwt],
            'json' => $body
        ], $schema);

        $response = new Response();
        $response->setSuccess($r['success'])->setError($r['error']);

        return $response;
    }

    /**
     * @param string $uid
     * @param string $jwt
     * @return Response
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function delete(string $uid, string $jwt): Response
    {
        $schema = new JsonSchema();
        $schema->setSchema([
            'success' => ['type' => JsonRule::BOOLEAN_TYPE],
            'error' => ['type' => JsonRule::STRING_TYPE, 'null' => true]
        ]);

        $r = $this->request('DELETE', $this->apiHost . self::API_URL_PREFIX . '/' . $uid, [
            'headers' => ['X-API-TOKEN' => $jwt]
        ], $schema);

        $response = new Response();
        $response->setSuccess($r['success'])->setError($r['error']);

        return $response;
    }
}
