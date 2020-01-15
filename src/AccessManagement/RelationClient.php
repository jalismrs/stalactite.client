<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\AccessManagement;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Rule\JsonRule;
use hunomina\Validator\Json\Schema\JsonSchema;
use Jalismrs\Stalactite\Client\AbstractClient;
use Jalismrs\Stalactite\Client\AccessManagement\Model\DomainRelation;
use Jalismrs\Stalactite\Client\ClientException;
use Jalismrs\Stalactite\Client\Response;

/**
 * RelationClient
 *
 * @package Jalismrs\Stalactite\Client\AccessManagement
 */
class RelationClient extends
    AbstractClient
{
    public const API_URL_PREFIX = Client::API_URL_PREFIX . '/relations';
    
    /**
     * @param DomainRelation $domainRelation
     * @param string         $jwt
     *
     * @return Response
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     * @throws ClientException
     */
    public function deleteRelation(DomainRelation $domainRelation, string $jwt) : Response
    {
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
            $this->host . self::API_URL_PREFIX . '/' . $domainRelation->getUid(),
            [
                'headers' => [
                    'X-API-TOKEN' => $jwt
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
