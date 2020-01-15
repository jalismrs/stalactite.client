<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\AccessManagement\Model;

use Jalismrs\Stalactite\Client\DataManagement\Model\User;

/**
 * DomainUserRelation
 *
 * @package Jalismrs\Stalactite\Client\AccessManagement\Model
 */
class DomainUserRelation extends
    DomainRelation
{
    /** @var User|null $user */
    private $user;
    
    /**
     * @return User|null
     */
    public function getUser() : ?User
    {
        return $this->user;
    }
    
    /**
     * @param User|null $user
     *
     * @return DomainUserRelation
     */
    public function setUser(?User $user) : DomainUserRelation
    {
        $this->user = $user;
        
        return $this;
    }
    
    /**
     * @return array
     * Return the object as an array
     */
    public function asArray() : array
    {
        return [
            'uid'    => $this->uid,
            'domain' => $this->domain
                ? $this->domain->asArray()
                : null,
            'user'   => $this->user
                ? $this->user->asArray()
                : null
        ];
    }
}
