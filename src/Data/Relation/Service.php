<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Data\Relation;

use Jalismrs\Stalactite\Client\AbstractService;
use Jalismrs\Stalactite\Client\Data\Model\DomainCustomerRelation;
use Jalismrs\Stalactite\Client\Data\Model\DomainUserRelation;
use Jalismrs\Stalactite\Client\Exception\ClientException;
use Jalismrs\Stalactite\Client\Exception\Service\DataServiceException;
use Jalismrs\Stalactite\Client\Util\Endpoint;
use Jalismrs\Stalactite\Client\Util\Response;
use Lcobucci\JWT\Token;
use Psr\SimpleCache\InvalidArgumentException;

/**
 * Class Service
 *
 * @package Jalismrs\Stalactite\Client\Data\Relation
 */
class Service extends
    AbstractService
{
    /**
     * @param DomainUserRelation $domainUserRelation
     * @param Token              $jwt
     *
     * @return Response
     * @throws ClientException
     * @throws InvalidArgumentException
     */
    public function deleteDomainUserRelation(
        DomainUserRelation $domainUserRelation,
        Token $jwt
    ) : Response {
        if ($domainUserRelation->getUid() === null) {
            throw new DataServiceException(
                'domain/user relation lacks a uid',
                DataServiceException::MISSING_DOMAIN_USER_RELATION_UID
            );
        }
        
        $endpoint = new Endpoint(
            '/data/relations/users/%s',
            'DELETE'
        );
        
        return $this->getClient()
                    ->request(
                        $endpoint,
                        [
                            'jwt'           => (string)$jwt,
                            'uriParameters' => $domainUserRelation->getUid(),
                        ]
                    );
    }
    
    /**
     * @param DomainCustomerRelation $domainCustomerRelation
     * @param Token                  $jwt
     *
     * @return Response
     * @throws ClientException
     * @throws InvalidArgumentException
     */
    public function deleteDomainCustomerRelation(
        DomainCustomerRelation $domainCustomerRelation,
        Token $jwt
    ) : Response {
        if ($domainCustomerRelation->getUid() === null) {
            throw new DataServiceException(
                'domain/customer relation lacks a uid',
                DataServiceException::MISSING_DOMAIN_CUSTOMER_RELATION_UID
            );
        }
        
        $endpoint = new Endpoint(
            '/data/relations/customers/%s',
            'DELETE'
        );
        
        return $this->getClient()
                    ->request(
                        $endpoint,
                        [
                            'jwt'           => (string)$jwt,
                            'uriParameters' => $domainCustomerRelation->getUid(),
                        ]
                    );
    }
}
