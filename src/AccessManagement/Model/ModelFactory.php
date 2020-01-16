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
     * @param array $data
     *
     * @return DomainUserRelationModel
     */
    public static function createDomainUserRelation(array $data) : DomainUserRelationModel
    {
        $model = new DomainUserRelationModel();
        $model
            ->setUser(
                isset($data['user'])
                    ? DataManagementModelFactory::createUser($data['user'])
                    : null
            )
            ->setDomain(
                isset($data['domain'])
                    ? DataManagementModelFactory::createDomain($data['domain'])
                    : null
            )
            ->setUid($data['uid'] ?? null);
        
        return $model;
    }
    
    /**
     * @param array $data
     *
     * @return DomainCustomerRelationModel
     */
    public static function createDomainCustomerRelation(array $data) : DomainCustomerRelationModel
    {
        $model = new DomainCustomerRelationModel();
        $model
            ->setCustomer(
                isset($data['customer'])
                    ? DataManagementModelFactory::createCustomer($data['customer'])
                    : null
            )
            ->setDomain(
                isset($data['domain'])
                    ? DataManagementModelFactory::createDomain($data['domain'])
                    : null
            )
            ->setUid($data['uid'] ?? null);
        
        return $model;
    }
    
    /**
     * createAccessClearance
     *
     * @static
     *
     * @param array $data
     *
     * @return \Jalismrs\Stalactite\Client\AccessManagement\Model\AccessClearanceModel
     */
    public static function createAccessClearance(array $data) : AccessClearanceModel
    {
        $model = new AccessClearanceModel();
        $model
            ->setAccess($data['accessGranted'] ?? false)
            ->setAccessType($data['accessType'] ?? AccessClearanceModel::NO_ACCESS);
        
        return $model;
    }
}
