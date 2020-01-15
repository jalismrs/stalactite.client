<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\AccessManagement\Model;

use Jalismrs\Stalactite\Client\AbstractModel;
use Jalismrs\Stalactite\Client\DataManagement\Model\Domain;

/**
 * DomainRelation
 *
 * @package Jalismrs\Stalactite\Client\AccessManagement\Model
 */
abstract class DomainRelation extends
    AbstractModel
{
    /** @var Domain|null $domain */
    protected $domain;
    
    /**
     * @return Domain|null
     */
    public function getDomain() : ?Domain
    {
        return $this->domain;
    }
    
    /**
     * @param Domain|null $domain
     *
     * @return DomainRelation
     */
    public function setDomain(?Domain $domain) : DomainRelation
    {
        $this->domain = $domain;
        
        return $this;
    }
}
