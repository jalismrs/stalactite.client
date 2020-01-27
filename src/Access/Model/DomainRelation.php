<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Access\Model;

use Jalismrs\Stalactite\Client\Data\Model\Domain;
use Jalismrs\Stalactite\Client\AbstractModel;

/**
 * DomainRelation
 *
 * @package Jalismrs\Stalactite\Client\Access\Model
 */
abstract class DomainRelation extends
    AbstractModel
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
     * setDomain
     *
     * @param null|Domain $domainModel
     *
     * @return DomainRelation
     */
    public function setDomain(?Domain $domainModel): self
    {
        $this->domain = $domainModel;

        return $this;
    }
}