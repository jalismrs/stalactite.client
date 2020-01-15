<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client;

/**
 * ModelAbstract
 *
 * @package Jalismrs\Stalactite\Client
 */
abstract class ModelAbstract
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
     * @return \Jalismrs\Stalactite\Client\ModelAbstract
     */
    public function setUid(?string $uid) : ModelAbstract
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
