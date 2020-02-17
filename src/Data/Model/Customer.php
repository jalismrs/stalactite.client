<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Data\Model;

use Jalismrs\Stalactite\Client\AbstractModel;

/**
 * Customer
 *
 * @package Jalismrs\Stalactite\Service\Data\Model
 */
class Customer extends
    AbstractModel
{

    private ?string $googleId = null;

    private ?string $email = null;

    private ?string $firstName = null;

    private ?string $lastName = null;

    /**
     * getGoogleId
     *
     * @return null|string
     */
    public function getGoogleId(): ?string
    {
        return $this->googleId;
    }

    /**
     * setGoogleId
     *
     * @param null|string $googleId
     *
     * @return $this
     */
    public function setGoogleId(?string $googleId): self
    {
        $this->googleId = $googleId;

        return $this;
    }

    /**
     * getEmail
     *
     * @return null|string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * setEmail
     *
     * @param null|string $email
     *
     * @return $this
     */
    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * getFirstName
     *
     * @return null|string
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * setFirstName
     *
     * @param null|string $firstName
     *
     * @return $this
     */
    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * getLastName
     *
     * @return null|string
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * setLastName
     *
     * @param null|string $lastName
     *
     * @return $this
     */
    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }
}
