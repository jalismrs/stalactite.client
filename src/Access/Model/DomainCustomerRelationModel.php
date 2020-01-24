<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Access\Model;

use Jalismrs\Stalactite\Client\Data\Model\CustomerModel;

/**
 * DomainCustomerRelationModel
 *
 * @package Jalismrs\Stalactite\Client\Access\Model
 */
class DomainCustomerRelationModel extends
    DomainRelationModelAbstract
{
    /**
     * @var null|CustomerModel
     */
    private $customer;

    /**
     * getCustomer
     *
     * @return null|CustomerModel
     */
    public function getCustomer(): ?CustomerModel
    {
        return $this->customer;
    }

    /**
     * setCustomer
     *
     * @param null|CustomerModel $customerModel
     *
     * @return $this
     */
    public function setCustomer(?CustomerModel $customerModel): self
    {
        $this->customer = $customerModel;

        return $this;
    }

    /**
     * asArray
     *
     * @return array
     */
    public function asArray(): array
    {
        return [
            'uid' => $this->uid,
            'domain' => null === $this->domain
                ? null
                : $this->domain->asArray(),
            'customer' => null === $this->customer
                ? null
                : $this->customer->asArray(),
        ];
    }
}
