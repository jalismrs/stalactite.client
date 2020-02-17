<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Data\Model;

use Jalismrs\Stalactite\Client\AbstractModel;

/**
 * Post
 *
 * @package Jalismrs\Stalactite\Service\Data\Model
 */
class Post extends
    AbstractModel
{
    private ?string $name = null;

    private ?string $shortName = null;

    private bool $allowAccess = false;

    private bool $adminAccess = false;

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
     * getShortName
     *
     * @return null|string
     */
    public function getShortName(): ?string
    {
        return $this->shortName;
    }

    /**
     * setShortName
     *
     * @param null|string $shortName
     *
     * @return $this
     */
    public function setShortName(?string $shortName): self
    {
        $this->shortName = $shortName;

        return $this;
    }

    /**
     * allowAccess
     *
     * @return bool
     */
    public function allowAccess(): bool
    {
        return $this->allowAccess;
    }

    /**
     * setAccess
     *
     * @param bool $allowAccess
     *
     * @return $this
     */
    public function setAccess(bool $allowAccess): self
    {
        $this->allowAccess = $allowAccess;

        return $this;
    }

    /**
     * hasAdminAccess
     *
     * @return bool
     */
    public function hasAdminAccess(): bool
    {
        return $this->adminAccess;
    }

    /**
     * setAdminAccess
     *
     * @param bool $adminAccess
     *
     * @return $this
     */
    public function setAdminAccess(bool $adminAccess): self
    {
        $this->adminAccess = $adminAccess;

        return $this;
    }
}
