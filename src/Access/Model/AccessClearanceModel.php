<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Access\Model;

/**
 * AccessClearanceModel
 *
 * @package Jalismrs\Stalactite\Client\Access\Model
 */
class AccessClearanceModel
{
    public const ADMIN_ACCESS = 'admin';
    public const NO_ACCESS = null;
    public const USER_ACCESS = 'user';

    /** @var bool $access */
    private $access;

    /** @var string|null $accessType */
    private $accessType;

    /**
     * AccessClearanceModel constructor.
     *
     * @param bool $hasAccess
     * @param string|null $accessType
     */
    public function __construct(
        bool $hasAccess = false,
        string $accessType = null
    )
    {
        $this->access = $hasAccess;
        $this->accessType = $accessType ?? self::NO_ACCESS;
    }

    /**
     * @return bool
     */
    public function allowAccess(): bool
    {
        return $this->access;
    }

    /**
     * @param bool $access
     *
     * @return AccessClearanceModel
     */
    public function setAccess(bool $access): self
    {
        $this->access = $access;

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
     * @return AccessClearanceModel
     */
    public function setAccessType(?string $accessType): self
    {
        $this->accessType = $accessType;

        return $this;
    }

    /**
     * @return bool
     */
    public function hasUserAccess(): bool
    {
        return $this->allowAccess() && $this->accessType === self::USER_ACCESS;
    }

    /**
     * @return bool
     */
    public function hasAdminAccess(): bool
    {
        return $this->allowAccess() && $this->accessType === self::ADMIN_ACCESS;
    }

    /**
     * asArray
     *
     * @return array
     */
    public function asArray(): array
    {
        return [
            'accessGranted' => $this->access,
            'accessType' => $this->accessType,
        ];
    }
}
