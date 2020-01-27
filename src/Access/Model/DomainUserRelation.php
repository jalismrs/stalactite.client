<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Access\Model;

use Jalismrs\Stalactite\Client\Data\Model\User;
use Jalismrs\Stalactite\Client\Util\Serializer;
use PHPUnit\Framework\Error\Deprecated;

/**
 * DomainUserRelation
 *
 * @package Jalismrs\Stalactite\Client\Access\Model
 */
class DomainUserRelation extends
    DomainRelation
{
    /**
     * @var null|User
     */
    private $user;
    
    /**
     * getUser
     *
     * @return null|User
     */
    public function getUser() : ?User
    {
        return $this->user;
    }
    
    /**
     * setUser
     *
     * @param null|User $userModel
     *
     * @return $this
     */
    public function setUser(?User $userModel) : self
    {
        $this->user = $userModel;
        
        return $this;
    }
    
    /**
     * asArray
     *
     * @return array
     *
     * @throws \PHPUnit\Framework\Error\Deprecated
     */
    public function asArray() : array
    {
        throw new Deprecated(
            'Reimplemented with Serializer',
            500,
            'Serializer.php',
            1
        );
    }
}
