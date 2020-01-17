<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\AccessManagement\Relation;

use hunomina\Validator\Json\Rule\JsonRule;
use hunomina\Validator\Json\Schema\JsonSchema;
use Jalismrs\Stalactite\Client\AccessManagement\Client as ParentClient;
use Jalismrs\Stalactite\Client\AccessManagement\Model\DomainRelationModelAbstract;
use Jalismrs\Stalactite\Client\ClientAbstract;
use Jalismrs\Stalactite\Client\Response;
use function vsprintf;

/**
 * Client
 *
 * @package Jalismrs\Stalactite\Client\AccessManagement\Relation
 */
class Client extends
    ClientAbstract
{
    public const API_URL_PART = ParentClient::API_URL_PART . '/relations';
    
    /**
     * deleteRelation
     *
     * @param \Jalismrs\Stalactite\Client\AccessManagement\Model\DomainRelationModelAbstract $domainRelationModel
     * @param string                                                                         $jwt
     *
     * @return \Jalismrs\Stalactite\Client\Response
     *
     * @throws \Jalismrs\Stalactite\Client\ClientException
     * @throws \hunomina\Validator\Json\Exception\InvalidDataTypeException
     * @throws \hunomina\Validator\Json\Exception\InvalidSchemaException
     */
    public function deleteRelation(
        DomainRelationModelAbstract $domainRelationModel,
        string $jwt
    ) : Response {
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
        
        $response = $this->requestDelete(
            vsprintf(
                '%s%s/%s',
                [
                    $this->host,
                    self::API_URL_PART,
                    $domainRelationModel->getUid(),
                ],
            ),
            [
                'headers' => [
                    'X-API-TOKEN' => $jwt
                ]
            ],
            $schema
        );
        
        return (new Response(
            $response['success'],
            $response['error']
        ));
    }
}
