<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Data\Model;

use hunomina\DataValidator\Rule\Json\JsonRule;
use Jalismrs\Stalactite\Client\AbstractModel;

/**
 * Post
 *
 * @package Jalismrs\Stalactite\Service\Data\Model
 */
class Post extends AbstractModel
{
    private ?string $name = null;
    private ?string $shortName = null;

    /**
     * @var Permission[]|array
     */
    private array $permissions = [];

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
     * @return array
     */
    public function getPermissions(): array
    {
        return $this->permissions;
    }

    /**
     * @param array $permissions
     * @return Post
     */
    public function setPermissions(array $permissions): Post
    {
        foreach ($permissions as $permission) {
            if ($permission instanceof Permission) {
                $this->addPermission($permission);
            }
        }

        return $this;
    }

    public function addPermission(Permission $permission): self
    {
        $this->permissions[] = $permission;
        return $this;
    }

    public function hasPermission(string $permission): bool
    {
        foreach ($this->permissions as $p) {
            if ((string)$p === $permission) {
                return true;
            }
        }

        return false;
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
            'shortName' => [
                'type' => JsonRule::STRING_TYPE
            ],
            'permissions' => [
                'type' => JsonRule::LIST_TYPE,
                'schema' => Permission::getSchema()
            ]
        ];
    }
}
