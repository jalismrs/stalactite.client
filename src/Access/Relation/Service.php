<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Access\Relation;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use Jalismrs\Stalactite\Client\AbstractService;
use Jalismrs\Stalactite\Client\Access\Model\DomainRelation;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\SerializerException;
use Jalismrs\Stalactite\Client\Util\Response;
use Jalismrs\Stalactite\Client\Util\Request;

/**
 * Service
 *
 * @package Jalismrs\Stalactite\Service\Access\Relation
 */
class Service extends
    AbstractService
{
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
     * @throws SerializerException
     */
    public function deleteRelation(
        DomainRelation $domainRelationModel,
        string $jwt
    ) : Response {
        return $this
            ->getClient()
            ->request(
                (new Request(
                    '/access/relations/%s'
                ))
                    ->setMethod('DELETE')
                    ->setOptions(
                        [
                            'headers' => [
                                'X-API-TOKEN' => $jwt
                            ]
                        ]
                    )
                    ->setUriDatas(
                        [
                            $domainRelationModel->getUid(),
                        ]
                    )
            );
    }
}
