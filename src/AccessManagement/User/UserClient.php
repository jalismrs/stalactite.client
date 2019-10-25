<?php

namespace jalismrs\Stalactite\Client\AccessManagement\User;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Rule\JsonRule;
use hunomina\Validator\Json\Schema\JsonSchema;
use jalismrs\Stalactite\Client\AbstractClient;
use jalismrs\Stalactite\Client\AccessManagement\Client;
use jalismrs\Stalactite\Client\AccessManagement\Model\ModelFactory;
use jalismrs\Stalactite\Client\AccessManagement\Schema;
use jalismrs\Stalactite\Client\ClientException;
use jalismrs\Stalactite\Client\DataManagement\Model\Domain;
use jalismrs\Stalactite\Client\DataManagement\Model\User;
use jalismrs\Stalactite\Client\DataManagement\Schema as DataManagementSchema;
use jalismrs\Stalactite\Client\Response;

class UserClient extends AbstractClient
{
    public const API_URL_PREFIX = Client::API_URL_PREFIX . '/users';

    /** @var MeClient $meClient */
    private $meClient;

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
     * @param User $user
     * @param string $jwt
     * @return Response
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function getRelations(User $user, string $jwt): Response
    {
        $schema = new JsonSchema();
        $schema->setSchema([
            'success' => ['type' => JsonRule::BOOLEAN_TYPE],
            'error' => ['type' => JsonRule::STRING_TYPE, 'null' => true],
            'relations' => ['type' => JsonRule::LIST_TYPE, 'schema' => [
                'uid' => ['type' => JsonRule::STRING_TYPE],
                'domain' => ['type' => JsonRule::OBJECT_TYPE, 'schema' => DataManagementSchema::DOMAIN]
            ]]
        ]);

        $r = $this->request('GET', $this->apiHost . self::API_URL_PREFIX . '/' . $user->getUid() . '/relations', [
            'headers' => ['X-API-TOKEN' => $jwt]
        ], $schema);

        $relations = [];
        foreach ($r['relations'] as $relation) {
            $relations[] = ModelFactory::createDomainUserRelation($relation)->setUser($user);
        }

        $response = new Response();
        $response->setSuccess($r['success'])->setError($r['error'])->setData([
            'relations' => $relations
        ]);

        return $response;
    }

    /**
     * @param User $user
     * @param Domain $domain
     * @param string $jwt
     * @return Response
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function getAccessClearance(User $user, Domain $domain, string $jwt): Response
    {
        $schema = new JsonSchema();
        $schema->setSchema([
            'success' => ['type' => JsonRule::BOOLEAN_TYPE],
            'error' => ['type' => JsonRule::STRING_TYPE, 'null' => true],
            'clearance' => ['type' => JsonRule::OBJECT_TYPE, 'schema' => Schema::ACCESS_CLEARANCE]
        ]);

        $r = $this->request('GET', $this->apiHost . self::API_URL_PREFIX . '/' . $user->getUid() . '/access/' . $domain->getUid(), [
            'headers' => ['X-API-TOKEN' => $jwt]
        ], $schema);

        $response = new Response();
        $response->setSuccess($r['success'])->setError($r['error'])->setData([
            'clearance' => ModelFactory::createAccessClearance($r['clearance'])
        ]);

        return $response;
    }
}