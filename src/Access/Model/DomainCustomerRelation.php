<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Access\Model;

use Jalismrs\Stalactite\Client\Data\Model\Customer;
use Jalismrs\Stalactite\Client\Util\Serializer;

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
     * @throws \Symfony\Component\Serializer\Exception\CircularReferenceException
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     * @throws \Symfony\Component\Serializer\Exception\InvalidArgumentException
     * @throws \Symfony\Component\Serializer\Exception\LogicException
     * @throws \Symfony\Component\Serializer\Exception\MappingException
     */
    public function asArray(): array
    {
        $serializer = Serializer::create();
        
        return [
            'uid' => $this->uid,
            'domain' => $serializer->normalize(
                $this->domain,
                [
                    'groups' => [
                        'main',
                    ],
                ]
            ),
            'customer' => $serializer->normalize(
                $this->customer,
                [
                    'groups' => [
                        'main',
                    ],
                ]
            ),
        ];
    }
}
