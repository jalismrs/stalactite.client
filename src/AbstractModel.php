<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client;

/**
 * AbstractModel
 *
 * @package Jalismrs\Stalactite\Service
 */
abstract class AbstractModel implements Schemable
{
    protected ?string $uid = null;

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
     * @return $this
     */
    public function setUid(?string $uid): self
    {
        $this->uid = $uid;

        return $this;
    }
}
