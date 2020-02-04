<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Access\Model;

use Jalismrs\Stalactite\Client\Data\Model\ModelFactory as DataModelFactory;

/**
 * ModelFactory
 *
 * @package Jalismrs\Stalactite\Service\Access\Model
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
    public static function createDomainUserRelation(array $data): DomainUserRelation
    {
        $model = new DomainUserRelation();
        $model
            ->setUser(
                isset($data['user'])
                    ? DataModelFactory::createUser($data['user'])
                    : null
            )
            ->setDomain(
                isset($data['domain'])
                    ? DataModelFactory::createDomain($data['domain'])
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
    public static function createDomainCustomerRelation(array $data): DomainCustomerRelation
    {
        $model = new DomainCustomerRelation();
        $model
            ->setCustomer(
                isset($data['customer'])
                    ? DataModelFactory::createCustomer($data['customer'])
                    : null
            )
            ->setDomain(
                isset($data['domain'])
                    ? DataModelFactory::createDomain($data['domain'])
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
    public static function createAccessClearance(array $data): AccessClearance
    {
        $model = new AccessClearance();
        $model
            ->setAccessGranted($data['accessGranted'] ?? false)
            ->setAccessType($data['accessType'] ?? AccessClearance::NO_ACCESS);

        return $model;
    }
}
