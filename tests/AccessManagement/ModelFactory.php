<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Test\AccessManagement;

use Jalismrs\Stalactite\Client\AccessManagement\Model\AccessClearanceModel;
use Jalismrs\Stalactite\Client\AccessManagement\Model\DomainCustomerRelationModel;
use Jalismrs\Stalactite\Client\AccessManagement\Model\DomainUserRelationModel;
use Jalismrs\Stalactite\Test\DataManagement\ModelFactory as DataManagementTestModelFactory;

/**
 * ModelFactory
 *
 * @package Jalismrs\Stalactite\Test\AccessManagement
 */
abstract class ModelFactory
{
    /**
     * @return DomainUserRelationModel
     */
    public static function getTestableDomainUserRelation() : DomainUserRelationModel
    {
        $model = new DomainUserRelationModel();
        $model
            ->setUser(DataManagementTestModelFactory::getTestableUser())
            ->setDomain(DataManagementTestModelFactory::getTestableDomain())
            ->setUid('azertyuiop');
        
        return $model;
    }
    
    /**
     * @return DomainCustomerRelationModel
     */
    public static function getTestableDomainCustomerRelation() : DomainCustomerRelationModel
    {
        $model = new DomainCustomerRelationModel();
        $model
            ->setCustomer(DataManagementTestModelFactory::getTestableCustomer())
            ->setDomain(DataManagementTestModelFactory::getTestableDomain())
            ->setUid('azertyuiop');
        
        return $model;
    }
    
    /**
     * @return AccessClearanceModel
     */
    public static function getTestableAccessClearance() : AccessClearanceModel
    {
        $model = new AccessClearanceModel();
        $model
            ->setAccess(false)
            ->setAccessType(AccessClearanceModel::NO_ACCESS);
        
        return $model;
    }
}
