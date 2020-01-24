<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Access\Model;

use Jalismrs\Stalactite\Client\Data\Model\ModelFactory as DataModelFactory;

/**
 * ModelFactory
 *
 * @package Jalismrs\Stalactite\Client\Access\Model
 */
abstract class ModelFactory
{
    /**
     * createDomainUserRelationModel
     *
     * @static
     *
     * @param array $data
     *
     * @return DomainUserRelation
     */
    public static function createDomainUserRelationModel(array $data): DomainUserRelation
    {
        $model = new DomainUserRelation();
        $model
            ->setUser(
                isset($data['user'])
                    ? DataModelFactory::createUserModel($data['user'])
                    : null
            )
            ->setDomain(
                isset($data['domain'])
                    ? DataModelFactory::createDomainModel($data['domain'])
                    : null
            )
            ->setUid($data['uid'] ?? null);

        return $model;
    }

    /**
     * createDomainCustomerRelationModel
     *
     * @static
     *
     * @param array $data
     *
     * @return DomainCustomerRelation
     */
    public static function createDomainCustomerRelationModel(array $data): DomainCustomerRelation
    {
        $model = new DomainCustomerRelation();
        $model
            ->setCustomer(
                isset($data['customer'])
                    ? DataModelFactory::createCustomerModel($data['customer'])
                    : null
            )
            ->setDomain(
                isset($data['domain'])
                    ? DataModelFactory::createDomainModel($data['domain'])
                    : null
            )
            ->setUid($data['uid'] ?? null);

        return $model;
    }

    /**
     * createAccessClearanceModel
     *
     * @static
     *
     * @param array $data
     *
     * @return AccessClearance
     */
    public static function createAccessClearanceModel(array $data): AccessClearance
    {
        $model = new AccessClearance();
        $model
            ->setAccess($data['accessGranted'] ?? false)
            ->setAccessType($data['accessType'] ?? AccessClearance::NO_ACCESS);

        return $model;
    }
}
