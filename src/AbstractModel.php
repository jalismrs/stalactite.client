<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client;

/**
 * AbstractModel
 *
 * @package Jalismrs\Stalactite\Client
 */
abstract class AbstractModel
{
    /**
     * @var null|string
     */
    protected $uid;
    
    /**
     * getUid
     *
     * @return null|string
     */
    public function getUid() : ?string
    {
        return $this->uid;
    }
    
    /**
     * setUid
     *
     * @param null|string $uid
     *
     * @return \Jalismrs\Stalactite\Client\AbstractModel
     */
    public function setUid(?string $uid) : AbstractModel
    {
        $this->uid = $uid;
        
        return $this;
    }
    
    /**
     * asArray
     *
     * @return array
     */
    abstract public function asArray() : array;
}
