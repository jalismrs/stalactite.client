<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Authentication\Model;

use hunomina\DataValidator\Rule\Json\JsonRule;
use Jalismrs\Stalactite\Client\AbstractModel;

/**
 * TrustedApp
 *
 * @package Jalismrs\Stalactite\Service\Authentication\Model
 */
class TrustedApp extends AbstractModel
{
    private ?string $name = null;
    private ?string $googleOAuthClientId = null;
    private ?string $authToken = null;
    private ?string $resetToken = null;

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

    public static function getSchema(): array
    {
        return [
            'uid' => [
                'type' => JsonRule::STRING_TYPE
            ],
            'name' => [
                'type' => JsonRule::STRING_TYPE
            ],
            'authToken' => [
                'type' => JsonRule::STRING_TYPE
            ],
            'googleOAuthClientId' => [
                'type' => JsonRule::STRING_TYPE
            ]
        ];
    }
}
