<?php

namespace jalismrs\Stalactite\Client\DataManagement\Model;

use jalismrs\Stalactite\Client\AbstractModel;

class Post extends AbstractModel
{
    /** @var null|string $name */
    private $name;

    /** @var null|string $shortName */
    private $shortName;

    /** @var bool $access */
    private $access = false;

    /** @var bool $admin */
    private $admin = false;

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     * @return Post
     */
    public function setName(?string $name): Post
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getShortName(): ?string
    {
        return $this->shortName;
    }

    /**
     * @param string|null $shortName
     * @return Post
     */
    public function setShortName(?string $shortName): Post
    {
        $this->shortName = $shortName;
        return $this;
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
     * @return Post
     */
    public function setAccess(bool $access): Post
    {
        $this->access = $access;
        return $this;
    }

    /**
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->admin;
    }

    /**
     * @param bool $admin
     * @return Post
     */
    public function setAdmin(bool $admin): Post
    {
        $this->admin = $admin;
        return $this;
    }

    /**
     * @return array
     */
    public function asArray(): array
    {
        return [
            'uid' => $this->uid,
            'name' => $this->name,
            'shortName' => $this->shortName,
            'admin' => $this->admin,
            'access' => $this->access
        ];
    }
}