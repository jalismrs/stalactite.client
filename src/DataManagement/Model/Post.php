<?php
declare(strict_types = 1);

namespace jalismrs\Stalactite\Client\DataManagement\Model;

use jalismrs\Stalactite\Client\AbstractModel;

class Post extends AbstractModel
{
    /** @var null|string $name */
    private $name;

    /** @var null|string $shortName */
    private $shortName;

    /** @var bool $allowAccess */
    private $allowAccess = false;

    /** @var bool $adminAccess */
    private $adminAccess = false;

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
        return $this->allowAccess;
    }

    /**
     * @param bool $allowAccess
     * @return Post
     */
    public function setAccess(bool $allowAccess): Post
    {
        $this->allowAccess = $allowAccess;
        return $this;
    }

    /**
     * @return bool
     */
    public function hasAdminAccess(): bool
    {
        return $this->adminAccess;
    }

    /**
     * @param bool $adminAccess
     * @return Post
     */
    public function setAdminAccess(bool $adminAccess): Post
    {
        $this->adminAccess = $adminAccess;
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
            'adminAccess' => $this->adminAccess,
            'allowAccess' => $this->allowAccess
        ];
    }
}
