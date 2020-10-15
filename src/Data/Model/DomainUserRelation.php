<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Data\Model;

use hunomina\DataValidator\Rule\Json\JsonRule;
use Jalismrs\Stalactite\Client\AbstractModel;

/**
 * Class DomainUserRelation
 *
 * @package Jalismrs\Stalactite\Client\Data\Model
 */
class DomainUserRelation extends
    AbstractModel
{
    /**
     * domain
     *
     * @var \Jalismrs\Stalactite\Client\Data\Model\Domain|null
     */
    private ?Domain $domain = null;
    /**
     * user
     *
     * @var \Jalismrs\Stalactite\Client\Data\Model\User|null
     */
    private ?User $user = null;
    
    /**
     * getSchema
     *
     * @static
     * @return array[]
     *
     * @codeCoverageIgnore
     */
    public static function getSchema() : array
    {
        return [
            'uid'    => [
                'type' => JsonRule::STRING_TYPE,
            ],
            'domain' => [
                'type'   => JsonRule::OBJECT_TYPE,
                'schema' => Domain::getSchema(),
            ],
            'user'   => [
                'type'   => JsonRule::OBJECT_TYPE,
                'schema' => User::getSchema(),
            ],
        ];
    }
    
    /**
     * getDomain
     *
     * @return \Jalismrs\Stalactite\Client\Data\Model\Domain|null
     *
     * @codeCoverageIgnore
     */
    public function getDomain() : ?Domain
    {
        return $this->domain;
    }
    
    /**
     * setDomain
     *
     * @param \Jalismrs\Stalactite\Client\Data\Model\Domain|null $domainModel
     *
     * @return $this
     *
     * @codeCoverageIgnore
     */
    public function setDomain(?Domain $domainModel) : self
    {
        $this->domain = $domainModel;
        
        return $this;
    }
    
    /**
     * getUser
     *
     * @return \Jalismrs\Stalactite\Client\Data\Model\User|null
     *
     * @codeCoverageIgnore
     */
    public function getUser() : ?User
    {
        return $this->user;
    }
    
    /**
     * setUser
     *
     * @param \Jalismrs\Stalactite\Client\Data\Model\User|null $userModel
     *
     * @return $this
     *
     * @codeCoverageIgnore
     */
    public function setUser(?User $userModel) : self
    {
        $this->user = $userModel;
        
        return $this;
    }
}
