<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Access\Model;

/**
 * AccessClearance
 *
 * @package Jalismrs\Stalactite\Service\Access\Model
 */
class AccessClearance
{
    public const NO_ACCESS = null;
    public const ADMIN_ACCESS = 'admin';
    public const USER_ACCESS = 'user';

    /** @var bool $granted */
    private $granted;

    /** @var string|null $type */
    private $type;

    /**
     * AccessClearance constructor.
     *
     * @param bool $granted
     * @param string|null $type
     */
    public function __construct(
        bool $granted = false,
        string $type = null
    )
    {
        $this->granted = $granted;
        $this->type = $type ?? self::NO_ACCESS;
    }

    /**
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param string|null $type
     *
     * @return AccessClearance
     */
    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return bool
     */
    public function hasUserAccessGranted(): bool
    {
        //TODO: not tested
        return $this->isGranted() && $this->type === self::USER_ACCESS;
    }

    /**
     * @return bool
     */
    public function isGranted(): bool
    {
        return $this->granted;
    }

    /**
     * setAccessGranted
     *
     * @param bool $granted
     *
     * @return $this
     */
    public function setGranted(bool $granted): self
    {
        $this->granted = $granted;

        return $this;
    }

    /**
     * @return bool
     */
    public function hasAdminAccessGranted(): bool
    {
        //TODO: not tested
        return $this->isGranted() && $this->type === self::ADMIN_ACCESS;
    }
}
