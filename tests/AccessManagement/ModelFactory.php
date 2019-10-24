<?php

namespace jalismrs\Stalactite\Client\Test\AccessManagement;

use jalismrs\Stalactite\Client\AccessManagement\Model\AccessClearance;
use jalismrs\Stalactite\Client\AccessManagement\Model\DomainCustomerRelation;
use jalismrs\Stalactite\Client\AccessManagement\Model\DomainUserRelation;
use jalismrs\Stalactite\Client\Test\DataManagement\ModelFactory as DataManagementTestModelFactory;

abstract class ModelFactory
{
    /**
     * @return DomainUserRelation
     */
    public static function getTestableDomainUserRelation(): DomainUserRelation
    {
        $dur = new DomainUserRelation();
        $dur->setUser(DataManagementTestModelFactory::getTestableUser())
            ->setDomain(DataManagementTestModelFactory::getTestableDomain())
            ->setUid('azertyuiop');

        return $dur;
    }

    /**
     * @return DomainCustomerRelation
     */
    public static function getTestableDomainCustomerRelation(): DomainCustomerRelation
    {
        $dur = new DomainCustomerRelation();
        $dur->setCustomer(DataManagementTestModelFactory::getTestableCustomer())
            ->setDomain(DataManagementTestModelFactory::getTestableDomain())
            ->setUid('azertyuiop');

        return $dur;
    }

    /**
     * @return AccessClearance
     */
    public static function getTestableAccessClearance(): AccessClearance
    {
        $ac = new AccessClearance();
        $ac->setAccess(false)
            ->setAccessType(AccessClearance::NO_ACCESS);

        return $ac;
    }
}