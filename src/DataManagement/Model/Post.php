<?php

namespace jalismrs\Stalactite\Client\DataManagement\Model;

use jalismrs\Stalactite\Client\AbstractModel;

class Post extends AbstractModel
{
    private const ADMIN_PRIVILEGE = 'admin';

    /** @var null|string $privilege */
    private $privilege;

    /** @var null|string $name */
    private $name;

    /** @var null|string $shortName */
    private $shortName;

    /** @var null|int $rank */
    private $rank;

    /**
     * @return string|null
     */
    public function getPrivilege(): ?string
    {
        return $this->privilege;
    }

    /**
     * @param string|null $privilege
     * @return Post
     */
    public function setPrivilege(?string $privilege): Post
    {
        $this->privilege = $privilege;
        return $this;
    }

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
     * @return int|null
     */
    public function getRank(): ?int
    {
        return $this->rank;
    }

    /**
     * @param int|null $rank
     * @return Post
     */
    public function setRank(?int $rank): Post
    {
        $this->rank = $rank;
        return $this;
    }

    /**
     * @return bool
     */
    public function hasAdminPrivilege(): bool
    {
        return $this->privilege === self::ADMIN_PRIVILEGE;
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
            'privilege' => $this->privilege,
            'rank' => $this->rank
        ];
    }
}