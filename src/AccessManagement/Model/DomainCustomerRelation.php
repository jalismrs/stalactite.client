<?php

namespace jalismrs\Stalactite\Client\AccessManagement\Model;

use jalismrs\Stalactite\Client\DataManagement\Model\Customer;

class DomainCustomerRelation extends DomainRelation
{
    /** @var Customer|null $customer */
    private $customer;

    /**
     * @return Customer|null
     */
    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    /**
     * @param Customer|null $customer
     * @return DomainCustomerRelation
     */
    public function setCustomer(?Customer $customer): DomainCustomerRelation
    {
        $this->customer = $customer;
        return $this;
    }

    /**
     * @return array
     * Return the object as an array
     */
    public function asArray(): array
    {
        return [
            'uid' => $this->uid,
            'domain' => $this->domain ? $this->domain->asArray() : null,
            'customer' => $this->customer ? $this->customer->asArray() : null
        ];
    }
}