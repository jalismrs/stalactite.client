<?php

namespace jalismrs\Stalactite\Client\AccessManagement\Model;

use jalismrs\Stalactite\Client\DataManagement\Model\ModelFactory as DataManagementModelFactory;

abstract class ModelFactory
{
    /**
     * @param array $data
     * @return DomainUserRelation
     */
    public static function createDomainUserRelation(array $data): DomainUserRelation
    {
        $dur = new DomainUserRelation();
        $dur->setUser(isset($data['user']) ? DataManagementModelFactory::createUser($data['user']) : null)
            ->setDomain(isset($data['domain']) ? DataManagementModelFactory::createDomain($data['domain']) : null)
            ->setUid($data['uid'] ?? null);

        return $dur;
    }

    /**
     * @param array $data
     * @return DomainCustomerRelation
     */
    public static function createDomainCustomerRelation(array $data): DomainCustomerRelation
    {
        $dcr = new DomainCustomerRelation();
        $dcr->setCustomer(isset($data['customer']) ? DataManagementModelFactory::createCustomer($data['customer']) : null)
            ->setDomain(isset($data['domain']) ? DataManagementModelFactory::createDomain($data['domain']) : null)
            ->setUid($data['uid'] ?? null);

        return $dcr;
    }
}