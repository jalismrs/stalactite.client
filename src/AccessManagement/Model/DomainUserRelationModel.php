<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\AccessManagement\Model;

use Jalismrs\Stalactite\Client\DataManagement\Model\UserModel;

/**
 * DomainUserRelationModel
 *
 * @package Jalismrs\Stalactite\Client\AccessManagement\Model
 */
class DomainUserRelationModel extends
    DomainRelationModelAbstract
{
    /** @var UserModel|null $user */
    private $user;
    
    /**
     * @return UserModel|null
     */
    public function getUser() : ?UserModel
    {
        return $this->user;
    }
    
    /**
     * @param UserModel|null $user
     *
     * @return DomainUserRelationModel
     */
    public function setUser(?UserModel $user) : DomainUserRelationModel
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
