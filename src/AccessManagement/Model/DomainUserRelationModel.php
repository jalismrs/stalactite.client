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
    /**
     * @var null|UserModel
     */
    private $user;
    
    /**
     * getUser
     *
     * @return null|\Jalismrs\Stalactite\Client\DataManagement\Model\UserModel
     */
    public function getUser() : ?UserModel
    {
        return $this->user;
    }
    
    /**
     * setUser
     *
     * @param null|\Jalismrs\Stalactite\Client\DataManagement\Model\UserModel $userModel
     *
     * @return $this
     */
    public function setUser(?UserModel $userModel) : self
    {
        $this->user = $userModel;
        
        return $this;
    }
    
    /**
     * asArray
     *
     * @return array
     */
    public function asArray() : array
    {
        return [
            'uid'    => $this->uid,
            'domain' => null === $this->domain
                ? null
                : $this->domain->asArray(),
            'user'   => null === $this->user
                ? null
                : $this->user->asArray(),
        ];
    }
}
