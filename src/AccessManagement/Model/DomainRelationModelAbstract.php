<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\AccessManagement\Model;

use Jalismrs\Stalactite\Client\ModelAbstract;
use Jalismrs\Stalactite\Client\DataManagement\Model\DomainModel;

/**
 * DomainRelationModelAbstract
 *
 * @package Jalismrs\Stalactite\Client\AccessManagement\Model
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
     * @param null|\Jalismrs\Stalactite\Client\DataManagement\Model\DomainModel $domainModel
     *
     * @return \Jalismrs\Stalactite\Client\AccessManagement\Model\DomainRelationModelAbstract
     */
    public function setDomain(?DomainModel $domainModel) : self
    {
        $this->domain = $domainModel;
        
        return $this;
    }
}
