<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\AccessManagement\AuthToken;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Rule\JsonRule;
use hunomina\Validator\Json\Schema\JsonSchema;
use Jalismrs\Stalactite\Client\AbstractClient;
use Jalismrs\Stalactite\Client\ClientException;
use Jalismrs\Stalactite\Client\DataManagement\Model\Domain;
use Jalismrs\Stalactite\Client\Response;

/**
 * DomainClient
 *
 * @package Jalismrs\Stalactite\Client\AccessManagement\AuthToken
 */
class DomainClient extends
    AbstractClient
{
    public const API_URL_PREFIX = AuthTokenClient::API_URL_PREFIX . '/domains';
    
    /**
     * @param Domain $domain
     * @param string $apiAuthToken
     *
     * @return Response
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     * @throws ClientException
     */
    public function deleteRelationsByDomain(Domain $domain, string $apiAuthToken) : Response
    {
        $jwt = AuthTokenClient::generateJwt(
            $apiAuthToken,
            $this->userAgent
        );
        
        $schema = new JsonSchema();
        $schema->setSchema(
            [
                'success' => [
                    'type' => JsonRule::BOOLEAN_TYPE
                ],
                'error'   => [
                    'type' => JsonRule::STRING_TYPE,
                    'null' => true
                ]
            ]
        );
    
        $r = $this->requestDelete(
            $this->host . self::API_URL_PREFIX . '/' . $domain->getUid() . '/relations',
            [
                'headers' => [
                    'X-API-TOKEN' => (string)$jwt
                ]
            ],
            $schema
        );
        
        return (new Response(
            $r['success'],
            $r['error']
        ));
    }
}
