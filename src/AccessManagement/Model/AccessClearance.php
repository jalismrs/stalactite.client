<?php

namespace jalismrs\Stalactite\Client\AccessManagement\Model;

class AccessClearance
{
    public const NO_ACCESS = null;
    public const USER_ACCESS = 'user';
    public const ADMIN_ACCESS = 'admin';

    /** @var bool $access */
    private $access;

    /** @var string|null $accessType */
    private $accessType;

    public function __construct(bool $hasAccess = false, ?string $accessType = self::NO_ACCESS)
    {
        $this->access = $hasAccess;
        $this->accessType = $accessType;
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
     * @return AccessClearance
     */
    public function setAccess(bool $access): AccessClearance
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
     * @return AccessClearance
     */
    public function setAccessType(?string $accessType): AccessClearance
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
     * @return array
     */
    public function asArray(): array
    {
        return [
            'accessGranted' => $this->access,
            'accessType' => $this->accessType
        ];
    }
}