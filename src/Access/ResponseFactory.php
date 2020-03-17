<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Access;

use Closure;
use Jalismrs\Stalactite\Client\Access\Model\DomainCustomerRelation;
use Jalismrs\Stalactite\Client\Access\Model\DomainUserRelation;
use Jalismrs\Stalactite\Client\Access\Model\ModelFactory;
use Jalismrs\Stalactite\Client\Data\Model\Domain;

/**
 * ResponseFactory
 *
 * @package Jalismrs\Stalactite\Client\Access
 */
abstract class ResponseFactory
{
    /**
     * @param Domain $domain
     * @return Closure
     */
    public static function domainGetRelations(Domain $domain): Closure
    {
        return static function (array $response) use ($domain) : array {
            return [
                'users' => array_map(
                    static function (array $relation) use ($domain): DomainUserRelation {
                        $domainUserRelation = ModelFactory::createDomainUserRelation($relation);
                        $domainUserRelation->setDomain($domain);

                        return $domainUserRelation;
                    },
                    $response['users']
                ),
                'customers' => array_map(
                    static function (array $relation) use ($domain): DomainCustomerRelation {
                        $domainCustomerRelation = ModelFactory::createDomainCustomerRelation($relation);
                        $domainCustomerRelation->setDomain($domain);

                        return $domainCustomerRelation;
                    },
                    $response['customers']
                )
            ];
        };
    }
}
