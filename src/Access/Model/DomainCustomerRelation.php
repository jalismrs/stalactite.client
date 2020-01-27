<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Access\Model;

use Jalismrs\Stalactite\Client\Data\Model\Customer;
use Jalismrs\Stalactite\Client\Util\Serializer;
use PHPUnit\Framework\Error\Deprecated;

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
    
    /**
     * asArray
     *
     * @return array
     *
     * @throws \PHPUnit\Framework\Error\Deprecated
     */
    public function asArray(): array
    {
        throw new Deprecated(
            'Reimplemented with Serializer',
            500,
            'Serializer.php',
            1
        );
    }
}
