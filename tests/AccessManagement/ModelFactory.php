<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Test\AccessManagement;

use Jalismrs\Stalactite\Client\AccessManagement\Model\AccessClearance;
use Jalismrs\Stalactite\Client\AccessManagement\Model\DomainCustomerRelation;
use Jalismrs\Stalactite\Client\AccessManagement\Model\DomainUserRelation;
use Jalismrs\Stalactite\Test\DataManagement\ModelFactory as DataManagementTestModelFactory;

/**
 * ModelFactory
 *
 * @package Jalismrs\Stalactite\Test\AccessManagement
 */
abstract class ModelFactory
{
    /**
     * @return DomainUserRelation
     */
    public static function getTestableDomainUserRelation() : DomainUserRelation
    {
        $model = new DomainUserRelation();
        $model
            ->setUser(DataManagementTestModelFactory::getTestableUser())
            ->setDomain(DataManagementTestModelFactory::getTestableDomain())
            ->setUid('azertyuiop');
        
        return $model;
    }
    
    /**
     * @return DomainCustomerRelation
     */
    public static function getTestableDomainCustomerRelation() : DomainCustomerRelation
    {
        $model = new DomainCustomerRelation();
        $model
            ->setCustomer(DataManagementTestModelFactory::getTestableCustomer())
            ->setDomain(DataManagementTestModelFactory::getTestableDomain())
            ->setUid('azertyuiop');
        
        return $model;
    }
    
    /**
     * @return AccessClearance
     */
    public static function getTestableAccessClearance() : AccessClearance
    {
        $model = new AccessClearance();
        $model
            ->setAccess(false)
            ->setAccessType(AccessClearance::NO_ACCESS);
        
        return $model;
    }
}
