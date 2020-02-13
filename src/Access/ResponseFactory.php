<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Access;

use Closure;
use Jalismrs\Stalactite\Client\AbstractService;
use Jalismrs\Stalactite\Client\Access\Model\DomainCustomerRelation;
use Jalismrs\Stalactite\Client\Access\Model\DomainUserRelation;
use Jalismrs\Stalactite\Client\Access\Model\ModelFactory;
use Jalismrs\Stalactite\Client\Data\Model\Domain;

/**
 * ResponseFactory
 *
 * @package Jalismrs\Stalactite\Client\Access
 */
class ResponseFactory extends
    AbstractService
{
    /**
     * domainGetRelations
     *
     * @static
     *
     * @param Domain $domainModel
     *
     * @return Closure
     */
    public static function domainGetRelations(
        Domain $domainModel
    ) : Closure {
        return static function(array $response) use ($domainModel) : array {
            return [
                'relations' => [
                    'users'     => array_map(
                        static function(array $relation) use ($domainModel): DomainUserRelation {
                            $domainUserRelationModel = ModelFactory::createDomainUserRelation($relation);
                            $domainUserRelationModel->setDomain($domainModel);
                            
                            return $domainUserRelationModel;
                        },
                        $response['relations']['users']
                    ),
                    'customers' => array_map(
                        static function(array $relation) use ($domainModel): DomainCustomerRelation {
                            $domainCustomerRelation = ModelFactory::createDomainCustomerRelation($relation);
                            $domainCustomerRelation->setDomain($domainModel);
                            
                            return $domainCustomerRelation;
                        },
                        $response['relations']['customers']
                    )
                ]
            ];
        };
    }
}
