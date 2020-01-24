<?php
declare(strict_types=1);

namespace Test\Access;

use Jalismrs\Stalactite\Client\Access\Model\AccessClearanceModel;
use Jalismrs\Stalactite\Client\Access\Model\DomainCustomerRelationModel;
use Jalismrs\Stalactite\Client\Access\Model\DomainUserRelationModel;
use Test\Data\ModelFactory as DataTestModelFactory;

/**
 * ModelFactory
 *
 * @package Test\Access
 */
abstract class ModelFactory
{
    /**
     * @return DomainUserRelationModel
     */
    public static function getTestableDomainUserRelation(): DomainUserRelationModel
    {
        $model = new DomainUserRelationModel();
        $model
            ->setUser(DataTestModelFactory::getTestableUser())
            ->setDomain(DataTestModelFactory::getTestableDomain())
            ->setUid('azertyuiop');

        return $model;
    }

    /**
     * @return DomainCustomerRelationModel
     */
    public static function getTestableDomainCustomerRelation(): DomainCustomerRelationModel
    {
        $model = new DomainCustomerRelationModel();
        $model
            ->setCustomer(DataTestModelFactory::getTestableCustomer())
            ->setDomain(DataTestModelFactory::getTestableDomain())
            ->setUid('azertyuiop');

        return $model;
    }

    /**
     * @return AccessClearanceModel
     */
    public static function getTestableAccessClearance(): AccessClearanceModel
    {
        $model = new AccessClearanceModel();
        $model
            ->setAccess(false)
            ->setAccessType(AccessClearanceModel::NO_ACCESS);

        return $model;
    }
}
