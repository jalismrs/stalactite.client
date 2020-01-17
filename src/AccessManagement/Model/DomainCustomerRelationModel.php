<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\AccessManagement\Model;

use Jalismrs\Stalactite\Client\DataManagement\Model\CustomerModel;

/**
 * DomainCustomerRelationModel
 *
 * @package Jalismrs\Stalactite\Client\AccessManagement\Model
 */
class DomainCustomerRelationModel extends
    DomainRelationModelAbstract
{
    /** @var CustomerModel|null $customer */
    private $customer;
    
    /**
     * @return CustomerModel|null
     */
    public function getCustomer() : ?CustomerModel
    {
        return $this->customer;
    }
    
    /**
     * @param CustomerModel|null $customer
     *
     * @return DomainCustomerRelationModel
     */
    public function setCustomer(?CustomerModel $customer) : DomainCustomerRelationModel
    {
        $this->customer = $customer;
        
        return $this;
    }
    
    /**
     * asArray
     *
     * @return array
     */
    public function asArray() : array
    {
        return [
            'uid'      => $this->uid,
            'domain'   => null === $this->domain
                ? null
                : $this->domain->asArray(),
            'customer' => null === $this->customer
                ? null
                : $this->customer->asArray(),
        ];
    }
}
