<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Access\Model;

use hunomina\DataValidator\Rule\Json\JsonRule;
use Jalismrs\Stalactite\Client\Data\Model\Customer;
use Jalismrs\Stalactite\Client\Data\Model\Domain;

/**
 * DomainCustomerRelation
 *
 * @package Jalismrs\Stalactite\Service\Access\Model
 */
class DomainCustomerRelation extends DomainRelation
{
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

    public static function getSchema(): array
    {
        return [
            'uid' => [
                'type' => JsonRule::STRING_TYPE
            ],
            'domain' => [
                'type' => JsonRule::OBJECT_TYPE,
                'schema' => Domain::getSchema()
            ],
            'customer' => [
                'type' => JsonRule::OBJECT_TYPE,
                'schema' => Customer::getSchema()
            ]
        ];
    }
}
