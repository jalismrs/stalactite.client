<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client;

/**
 * Class AbstractModel
 *
 * @package Jalismrs\Stalactite\Client
 */
abstract class AbstractModel implements
    Schemable
{
    /**
     * uid
     *
     * @var string|null
     */
    protected ?string $uid = null;
    
    /**
     * getUid
     *
     * @return string|null
     *
     * @codeCoverageIgnore
     */
    public function getUid() : ?string
    {
        return $this->uid;
    }
    
    /**
     * setUid
     *
     * @param string|null $uid
     *
     * @return $this
     *
     * @codeCoverageIgnore
     */
    public function setUid(?string $uid) : self
    {
        $this->uid = $uid;
        
        return $this;
    }
}
