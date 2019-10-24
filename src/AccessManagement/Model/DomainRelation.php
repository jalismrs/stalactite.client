<?php

namespace jalismrs\Stalactite\Client\AccessManagement\Model;

use jalismrs\Stalactite\Client\AbstractModel;
use jalismrs\Stalactite\Client\DataManagement\Model\Domain;

abstract class DomainRelation extends AbstractModel
{
    /** @var Domain|null $domain */
    protected $domain;

    /**
     * @return Domain|null
     */
    public function getDomain(): ?Domain
    {
        return $this->domain;
    }

    /**
     * @param Domain|null $domain
     * @return DomainRelation
     */
    public function setDomain(?Domain $domain): DomainRelation
    {
        $this->domain = $domain;
        return $this;
    }

    /**
     * @return array
     * Return the object as an array
     */
    abstract public function asArray(): array;
}