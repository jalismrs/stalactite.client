<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Access\Relation;

use Jalismrs\Stalactite\Client\AbstractService;
use Jalismrs\Stalactite\Client\Access\Model\DomainRelation;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\Service\AccessServiceException;
use Jalismrs\Stalactite\Client\Util\Endpoint;
use Jalismrs\Stalactite\Client\Util\Response;

/**
 * Service
 *
 * @package Jalismrs\Stalactite\Service\Access\Relation
 */
class Service extends AbstractService
{
    /**
     * @param DomainRelation $domainRelation
     * @param string $jwt
     * @return Response
     * @throws ClientException
     */
    public function deleteRelation(DomainRelation $domainRelation, string $jwt): Response
    {
        if ($domainRelation->getUid() === null) {
            throw new AccessServiceException('DomainRelation lacks a uid', AccessServiceException::MISSING_DOMAIN_RELATION_UID);
        }

        $endpoint = new Endpoint('/access/relations/%s', 'DELETE');

        return $this->getClient()->request($endpoint, [
            'jwt' => $jwt,
            'uriParameters' => $domainRelation->getUid()
        ]);
    }
}
