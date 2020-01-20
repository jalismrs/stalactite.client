<?php
declare(strict_types = 1);

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
     * @return \Jalismrs\Stalactite\Client\Access\Model\DomainUserRelationModel
     */
    public static function createDomainUserRelationModel(array $data) : DomainUserRelationModel
    {
        $model = new DomainUserRelationModel();
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
     * @return \Jalismrs\Stalactite\Client\Access\Model\DomainCustomerRelationModel
     */
    public static function createDomainCustomerRelationModel(array $data) : DomainCustomerRelationModel
    {
        $model = new DomainCustomerRelationModel();
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
     * @return \Jalismrs\Stalactite\Client\Access\Model\AccessClearanceModel
     */
    public static function createAccessClearanceModel(array $data) : AccessClearanceModel
    {
        $model = new AccessClearanceModel();
        $model
            ->setAccess($data['accessGranted'] ?? false)
            ->setAccessType($data['accessType'] ?? AccessClearanceModel::NO_ACCESS);
        
        return $model;
    }
}
