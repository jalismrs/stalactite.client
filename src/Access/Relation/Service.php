<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Access\Relation;

use Jalismrs\Stalactite\Client\AbstractService;
use Jalismrs\Stalactite\Client\Access\Model\DomainRelation;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\Service\AccessServiceException;
use Jalismrs\Stalactite\Client\Util\Endpoint;
use Jalismrs\Stalactite\Client\Util\Response;
use Lcobucci\JWT\Token;

/**
 * Service
 *
 * @package Jalismrs\Stalactite\Service\Access\Relation
 */
class Service extends AbstractService
{
    /**
     * @param DomainRelation $domainRelation
     * @param Token $jwt
     * @return Response
     * @throws ClientException
     */
    public function deleteRelation(DomainRelation $domainRelation, Token $jwt): Response
    {
        if ($domainRelation->getUid() === null) {
            throw new AccessServiceException('DomainRelation lacks a uid', AccessServiceException::MISSING_DOMAIN_RELATION_UID);
        }

        $endpoint = new Endpoint('/access/relations/%s', 'DELETE');

        return $this->getClient()->request($endpoint, [
            'jwt' => (string)$jwt,
            'uriParameters' => $domainRelation->getUid()
        ]);
    }
}
