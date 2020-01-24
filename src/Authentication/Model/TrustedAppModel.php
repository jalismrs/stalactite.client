<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Authentication\Model;

use Jalismrs\Stalactite\Client\ModelAbstract;

/**
 * TrustedAppModel
 *
 * @package Jalismrs\Stalactite\Client\Authentication\Model
 */
class TrustedAppModel extends
    ModelAbstract
{
    /**
     * @var null|string
     */
    private $name;
    /**
     * @var null|string
     */
    private $googleOAuthClientId;
    /**
     * @var null|string
     */
    private $authToken;
    /**
     * @var null|string
     */
    private $resetToken;

    /**
     * getName
     *
     * @return null|string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * setName
     *
     * @param null|string $name
     *
     * @return $this
     */
    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * getGoogleOAuthClientId
     *
     * @return null|string
     */
    public function getGoogleOAuthClientId(): ?string
    {
        return $this->googleOAuthClientId;
    }

    /**
     * setGoogleOAuthClientId
     *
     * @param null|string $googleOAuthClientId
     *
     * @return $this
     */
    public function setGoogleOAuthClientId(?string $googleOAuthClientId): self
    {
        $this->googleOAuthClientId = $googleOAuthClientId;

        return $this;
    }

    /**
     * getAuthToken
     *
     * @return null|string
     */
    public function getAuthToken(): ?string
    {
        return $this->authToken;
    }

    /**
     * setAuthToken
     *
     * @param null|string $authToken
     *
     * @return $this
     */
    public function setAuthToken(?string $authToken): self
    {
        $this->authToken = $authToken;

        return $this;
    }

    /**
     * getResetToken
     *
     * @return null|string
     */
    public function getResetToken(): ?string
    {
        return $this->resetToken;
    }

    /**
     * setResetToken
     *
     * @param null|string $resetToken
     *
     * @return $this
     */
    public function setResetToken(?string $resetToken): self
    {
        $this->resetToken = $resetToken;

        return $this;
    }

    /**
     * asArray
     *
     * @return array
     */
    public function asArray(): array
    {
        return [
            'uid' => $this->uid,
            'name' => $this->name,
            'authToken' => $this->authToken,
            'googleOAuthClientId' => $this->googleOAuthClientId,
        ];
    }
}
