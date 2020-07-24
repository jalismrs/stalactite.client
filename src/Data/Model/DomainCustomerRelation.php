<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Data\Model;

use hunomina\DataValidator\Rule\Json\JsonRule;
use Jalismrs\Stalactite\Client\AbstractModel;

class DomainCustomerRelation extends AbstractModel
{
    protected ?Domain $domain = null;
    private ?Customer $customer = null;

    public function getDomain(): ?Domain
    {
        return $this->domain;
    }

    public function setDomain(?Domain $domainModel): self
    {
        $this->domain = $domainModel;

        return $this;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

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
