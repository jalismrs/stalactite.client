<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Access\Model;

use Jalismrs\Stalactite\Client\Data\Model\Customer;

/**
 * DomainCustomerRelation
 *
 * @package Jalismrs\Stalactite\Service\Access\Model
 */
class DomainCustomerRelation extends
    DomainRelation
{
    /**
     * @var Customer|null
     */
    private ?Customer $customer = null;

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
