<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Access\Relation;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Rule\JsonRule;
use hunomina\Validator\Json\Schema\JsonSchema;
use Jalismrs\Stalactite\Client\AbstractService;
use Jalismrs\Stalactite\Client\Access\Model\DomainRelation;
use Jalismrs\Stalactite\Client\ClientException;
use Jalismrs\Stalactite\Client\Response;
use function vsprintf;

/**
 * Service
 *
 * @package Jalismrs\Stalactite\Service\Access\Relation
 */
class Service extends
    AbstractService
{
    private const REQUEST_DELETE_RELATION_CONFIGURATION = [
        'endpoint' => '/access/relations/%s',
        'method'   => 'DELETE',
    ];
    
    /**
     * deleteRelation
     *
     * @param DomainRelation $domainRelationModel
     * @param string         $jwt
     *
     * @return Response
     *
     * @throws ClientException
     * @throws InvalidDataTypeException
     * @throws InvalidSchemaException
     */
    public function deleteRelation(
        DomainRelation $domainRelationModel,
        string $jwt
    ) : Response {
        $response = $this
            ->getClient()
            ->request(
                self::REQUEST_DELETE_RELATION_CONFIGURATION,
                [
                    $domainRelationModel->getUid(),
                ],
                [
                    'headers' => [
                        'X-API-TOKEN' => $jwt
                    ]
                ]
            );
        
        return (new Response(
            $response['success'],
            $response['error']
        ));
    }
}
