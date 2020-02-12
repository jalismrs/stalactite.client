<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Access\Relation;

use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use Jalismrs\Stalactite\Client\AbstractService;
use Jalismrs\Stalactite\Client\Access\Model\DomainRelation;
use Jalismrs\Stalactite\Client\Client;
use Jalismrs\Stalactite\Client\ClientException;
use Jalismrs\Stalactite\Client\Exception\RequestConfigurationException;
use Jalismrs\Stalactite\Client\RequestConfiguration;
use Jalismrs\Stalactite\Client\Response;
use Jalismrs\Stalactite\Client\Util\SerializerException;

/**
 * Service
 *
 * @package Jalismrs\Stalactite\Service\Access\Relation
 */
class Service extends
    AbstractService
{
    /**
     * Service constructor.
     *
     * @param Client $client
     *
     * @throws RequestConfigurationException
     */
    public function __construct(
        Client $client
    ) {
        parent::__construct(
            $client
        );
        
        $this->requestConfigurations = [
            'deleteRelation' => (new RequestConfiguration(
                '/access/relations/%s'
            ))
                ->setMethod('DELETE'),
        ];
    }
    
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
                $this->requestConfigurations['deleteRelation'],
                [
                    $domainRelationModel->getUid(),
                ],
                [
                    'headers' => [
                        'X-API-TOKEN' => $jwt
                    ]
                ]
            );
    }
}
