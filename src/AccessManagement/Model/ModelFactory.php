<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\AccessManagement\Model;

use Jalismrs\Stalactite\Client\DataManagement\Model\ModelFactory as DataManagementModelFactory;

/**
 * ModelFactory
 *
 * @package Jalismrs\Stalactite\Client\AccessManagement\Model
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
     * @return \Jalismrs\Stalactite\Client\AccessManagement\Model\DomainUserRelationModel
     */
    public static function createDomainUserRelationModel(array $data) : DomainUserRelationModel
    {
        $model = new DomainUserRelationModel();
        $model
            ->setUser(
                isset($data['user'])
                    ? DataManagementModelFactory::createUserModel($data['user'])
                    : null
            )
            ->setDomain(
                isset($data['domain'])
                    ? DataManagementModelFactory::createDomainModel($data['domain'])
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
     * @return \Jalismrs\Stalactite\Client\AccessManagement\Model\DomainCustomerRelationModel
     */
    public static function createDomainCustomerRelationModel(array $data) : DomainCustomerRelationModel
    {
        $model = new DomainCustomerRelationModel();
        $model
            ->setCustomer(
                isset($data['customer'])
                    ? DataManagementModelFactory::createCustomerModel($data['customer'])
                    : null
            )
            ->setDomain(
                isset($data['domain'])
                    ? DataManagementModelFactory::createDomainModel($data['domain'])
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
     * @return \Jalismrs\Stalactite\Client\AccessManagement\Model\AccessClearanceModel
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
