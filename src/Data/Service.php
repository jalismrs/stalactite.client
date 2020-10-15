<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Data;

use Jalismrs\Stalactite\Client\AbstractService;

/**
 * Class Service
 *
 * @package Jalismrs\Stalactite\Client\Data
 */
class Service extends
    AbstractService
{
    private ?Customer\Service $serviceCustomer = null;
    
    private ?Domain\Service $serviceDomain = null;
    
    private ?Post\Service $servicePost = null;
    
    private ?User\Service $serviceUser = null;
    
    private ?Permission\Service $servicePermission = null;
    
    private ?Relation\Service $serviceRelation = null;
    
    /*
     * -------------------------------------------------------------------------
     * Clients -----------------------------------------------------------------
     * -------------------------------------------------------------------------
     */
    
    public function customers() : Customer\Service
    {
        if ($this->serviceCustomer === null) {
            $this->serviceCustomer = new Customer\Service($this->getClient());
        }
        
        return $this->serviceCustomer;
    }
    
    public function domains() : Domain\Service
    {
        if ($this->serviceDomain === null) {
            $this->serviceDomain = new Domain\Service($this->getClient());
        }
        
        return $this->serviceDomain;
    }
    
    public function posts() : Post\Service
    {
        if ($this->servicePost === null) {
            $this->servicePost = new Post\Service($this->getClient());
        }
        
        return $this->servicePost;
    }
    
    public function users() : User\Service
    {
        if ($this->serviceUser === null) {
            $this->serviceUser = new User\Service($this->getClient());
        }
        
        return $this->serviceUser;
    }
    
    public function permissions() : Permission\Service
    {
        if ($this->servicePermission === null) {
            $this->servicePermission = new Permission\Service($this->getClient());
        }
        
        return $this->servicePermission;
    }
    
    public function relations() : Relation\Service
    {
        if ($this->serviceRelation === null) {
            $this->serviceRelation = new Relation\Service($this->getClient());
        }
        
        return $this->serviceRelation;
    }
}
