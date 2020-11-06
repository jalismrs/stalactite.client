<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Data\Model;

use hunomina\DataValidator\Rule\Json\JsonRule;
use Jalismrs\Stalactite\Client\AbstractModel;

/**
 * Class DomainCustomerRelation
 *
 * @package Jalismrs\Stalactite\Client\Data\Model
 */
class DomainCustomerRelation extends
    AbstractModel
{
    /**
     * domain
     *
     * @var Domain|null
     */
    protected ?Domain $domain = null;
    /**
     * customer
     *
     * @var Customer|null
     */
    private ?Customer $customer = null;

    /**
     * getSchema
     *
     * @static
     * @return array[]
     *
     * @codeCoverageIgnore
     */
    public static function getSchema(): array
    {
        return [
            'uid' => [
                'type' => JsonRule::STRING_TYPE,
            ],
            'domain' => [
                'type' => JsonRule::OBJECT_TYPE,
                'schema' => Domain::getSchema(),
            ],
            'customer' => [
                'type' => JsonRule::OBJECT_TYPE,
                'schema' => Customer::getSchema(),
            ],
        ];
    }

    /**
     * getDomain
     *
     * @return Domain|null
     *
     * @codeCoverageIgnore
     */
    public function getDomain(): ?Domain
    {
        return $this->domain;
    }

    /**
     * setDomain
     *
     * @param Domain|null $domainModel
     *
     * @return $this
     *
     * @codeCoverageIgnore
     */
    public function setDomain(?Domain $domainModel): self
    {
        $this->domain = $domainModel;

        return $this;
    }

    /**
     * getCustomer
     *
     * @return Customer|null
     *
     * @codeCoverageIgnore
     */
    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    /**
     * setCustomer
     *
     * @param Customer|null $customerModel
     *
     * @return $this
     *
     * @codeCoverageIgnore
     */
    public function setCustomer(?Customer $customerModel): self
    {
        $this->customer = $customerModel;

        return $this;
    }
}
