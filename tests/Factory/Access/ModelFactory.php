<?php

namespace Jalismrs\Stalactite\Client\Tests\Factory\Access;

use Jalismrs\Stalactite\Client\Access\Model\AccessClearance;
use Jalismrs\Stalactite\Client\Access\Model\DomainCustomerRelation;
use Jalismrs\Stalactite\Client\Access\Model\DomainUserRelation;
use Jalismrs\Stalactite\Client\Tests\Factory\Data\ModelFactory as DataTestModelFactory;

abstract class ModelFactory
{
    /**
     * @return DomainUserRelation
     */
    public static function getTestableDomainUserRelation(): DomainUserRelation
    {
        $model = new DomainUserRelation();
        $model
            ->setUser(DataTestModelFactory::getTestableUser())
            ->setDomain(DataTestModelFactory::getTestableDomain())
            ->setUid('azertyuiop');

        return $model;
    }

    /**
     * @return DomainCustomerRelation
     */
    public static function getTestableDomainCustomerRelation(): DomainCustomerRelation
    {
        $model = new DomainCustomerRelation();
        $model
            ->setCustomer(DataTestModelFactory::getTestableCustomer())
            ->setDomain(DataTestModelFactory::getTestableDomain())
            ->setUid('azertyuiop');

        return $model;
    }

    /**
     * @return AccessClearance
     */
    public static function getTestableAccessClearance(): AccessClearance
    {
        $model = new AccessClearance();
        $model
            ->setGranted(false)
            ->setType(AccessClearance::NO_ACCESS);

        return $model;
    }
}
