<?php

namespace jalismrs\Stalactite\Client\Authentication\Model;

class TrustedApp
{
    /** @var null|string $uid */
    private $uid;

    /** @var null|string $name */
    private $name;

    /** @var null|string $googleOAuthClientId */
    private $googleOAuthClientId;

    /** @var null|string $authToken */
    private $authToken;

    /** @var null|string $resetToken */
    private $resetToken;

    /**
     * @return string
     */
    public function getUid(): ?string
    {
        return $this->uid;
    }

    /**
     * @param string $uid
     * @return TrustedApp
     */
    public function setUid(?string $uid): TrustedApp
    {
        $this->uid = $uid;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return TrustedApp
     */
    public function setName(?string $name): TrustedApp
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getGoogleOAuthClientId(): ?string
    {
        return $this->googleOAuthClientId;
    }

    /**
     * @param string $googleOAuthClientId
     * @return TrustedApp
     */
    public function setGoogleOAuthClientId(?string $googleOAuthClientId): TrustedApp
    {
        $this->googleOAuthClientId = $googleOAuthClientId;
        return $this;
    }

    /**
     * @return string
     */
    public function getAuthToken(): ?string
    {
        return $this->authToken;
    }

    /**
     * @param string $authToken
     * @return TrustedApp
     */
    public function setAuthToken(?string $authToken): TrustedApp
    {
        $this->authToken = $authToken;
        return $this;
    }

    /**
     * @return string
     */
    public function getResetToken(): ?string
    {
        return $this->resetToken;
    }

    /**
     * @param string $resetToken
     * @return TrustedApp
     */
    public function setResetToken(?string $resetToken): TrustedApp
    {
        $this->resetToken = $resetToken;
        return $this;
    }
}