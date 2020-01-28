<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Access\Model;

use Jalismrs\Stalactite\Client\Data\Model\Customer;

/**
 * DomainCustomerRelation
 *
 * @package Jalismrs\Stalactite\Client\Access\Model
 */
class DomainCustomerRelation extends
    DomainRelation
{
    /**
     * @var null|Customer
     */
    private $customer;

    /**
     * getCustomer
     *
     * @return null|Customer
     */
    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    /**
     * setCustomer
     *
     * @param null|Customer $customerModel
     *
     * @return $this
     */
    public function setCustomer(?Customer $customerModel): self
    {
        $this->customer = $customerModel;

        return $this;
    }
}
