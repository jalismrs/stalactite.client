<?php
declare(strict_types = 1);

namespace jalismrs\Stalactite\Client;

/**
 * AbstractModel
 *
 * @package jalismrs\Stalactite\Client
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
    public function getUid(): ?string
    {
        return $this->uid;
    }
    
    /**
     * setUid
     *
     * @param null|string $uid
     *
     * @return \jalismrs\Stalactite\Client\AbstractModel
     */
    public function setUid(?string $uid): AbstractModel
    {
        $this->uid = $uid;
        return $this;
    }
    
    /**
     * asArray
     *
     * @return array
     */
    abstract public function asArray(): array;
}
