<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Access\Model;

/**
 * AccessClearance
 *
 * @package Jalismrs\Stalactite\Client\Access\Model
 */
class AccessClearance
{
    public const NO_ACCESS = null;
    public const ADMIN_ACCESS = 'admin';
    public const USER_ACCESS = 'user';

    /** @var bool $accessGranted */
    private $accessGranted;

    /** @var string|null $accessType */
    private $accessType;

    /**
     * AccessClearance constructor.
     *
     * @param bool $accessGranted
     * @param string|null $accessType
     */
    public function __construct(
        bool $accessGranted = false,
        string $accessType = null
    )
    {
        $this->accessGranted = $accessGranted;
        $this->accessType = $accessType ?? self::NO_ACCESS;
    }

    /**
     * @return bool
     */
    public function hasAccessGranted(): bool
    {
        return $this->accessGranted;
    }

    /**
     * setAccessGranted
     *
     * @param bool $accessGranted
     *
     * @return $this
     */
    public function setAccessGranted(bool $accessGranted): self
    {
        $this->accessGranted = $accessGranted;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getAccessType(): ?string
    {
        return $this->accessType;
    }

    /**
     * @param string|null $accessType
     *
     * @return AccessClearance
     */
    public function setAccessType(?string $accessType): self
    {
        $this->accessType = $accessType;

        return $this;
    }

    /**
     * @return bool
     */
    public function hasUserAccessGranted(): bool
    {
        return $this->hasAccessGranted() && $this->accessType === self::USER_ACCESS;
    }

    /**
     * @return bool
     */
    public function hasAdminAccessGranted(): bool
    {
        return $this->hasAccessGranted() && $this->accessType === self::ADMIN_ACCESS;
    }
}
