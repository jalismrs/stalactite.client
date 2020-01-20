<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Access\Model;

use Jalismrs\Stalactite\Client\ModelAbstract;
use Jalismrs\Stalactite\Client\Data\Model\DomainModel;

/**
 * DomainRelationModelAbstract
 *
 * @package Jalismrs\Stalactite\Client\Access\Model
 */
abstract class DomainRelationModelAbstract extends
    ModelAbstract
{
    /** @var DomainModel|null $domain */
    protected $domain;
    
    /**
     * @return DomainModel|null
     */
    public function getDomain() : ?DomainModel
    {
        return $this->domain;
    }
    
    /**
     * setDomain
     *
     * @param null|\Jalismrs\Stalactite\Client\Data\Model\DomainModel $domainModel
     *
     * @return \Jalismrs\Stalactite\Client\Access\Model\DomainRelationModelAbstract
     */
    public function setDomain(?DomainModel $domainModel) : self
    {
        $this->domain = $domainModel;
        
        return $this;
    }
}
