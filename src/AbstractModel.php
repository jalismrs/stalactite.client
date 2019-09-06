<?php

namespace jalismrs\Stalactite\Client;

abstract class AbstractModel
{
    /** @var null|string */
    protected $uid;

    /**
     * @return string|null
     */
    public function getUid(): ?string
    {
        return $this->uid;
    }

    /**
     * @param string|null $uid
     * @return AbstractModel
     */
    public function setUid(?string $uid): AbstractModel
    {
        $this->uid = $uid;
        return $this;
    }

    /**
     * @return array
     * Return the object as an array
     */
    abstract public function asArray(): array;
}