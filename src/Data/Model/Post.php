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
class Post extends
    AbstractModel
{
    /**
     * name
     *
     * @var string|null
     */
    private ?string $name = null;
    /**
     * shortName
     *
     * @var string|null
     */
    private ?string $shortName = null;
    /**
     * @var Permission[]|array
     */
    private array $permissions = [];

    /**
     * getSchema
     *
     * @static
     * @return array[]
     *
     * @codeCoverageIgnore
     */
    public static function getSchema(): array
    {
        return [
            'uid' => [
                'type' => JsonRule::STRING_TYPE,
            ],
            'name' => [
                'type' => JsonRule::STRING_TYPE,
            ],
            'shortName' => [
                'type' => JsonRule::STRING_TYPE,
            ],
            'permissions' => [
                'type' => JsonRule::LIST_TYPE,
                'schema' => Permission::getSchema(),
            ],
        ];
    }

    /**
     * getName
     *
     * @return string|null
     *
     * @codeCoverageIgnore
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * setName
     *
     * @param string|null $name
     *
     * @return $this
     *
     * @codeCoverageIgnore
     */
    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * getShortName
     *
     * @return string|null
     *
     * @codeCoverageIgnore
     */
    public function getShortName(): ?string
    {
        return $this->shortName;
    }

    /**
     * setShortName
     *
     * @param string|null $shortName
     *
     * @return $this
     *
     * @codeCoverageIgnore
     */
    public function setShortName(?string $shortName): self
    {
        $this->shortName = $shortName;

        return $this;
    }

    /**
     * getPermissions
     *
     * @return array|Permission[]
     *
     * @codeCoverageIgnore
     */
    public function getPermissions(): array
    {
        return $this->permissions;
    }

    /**
     * setPermissions
     *
     * @param array $permissions
     *
     * @return $this
     *
     * @codeCoverageIgnore
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

    /**
     * addPermission
     *
     * @param Permission $permission
     *
     * @return $this
     *
     * @codeCoverageIgnore
     */
    public function addPermission(Permission $permission): self
    {
        $this->permissions[] = $permission;

        return $this;
    }

    /**
     * hasPermission
     *
     * @param string $permission
     *
     * @return bool
     */
    public function hasPermission(string $permission): bool
    {
        foreach ($this->permissions as $p) {
            if ((string)$p === $permission) {
                return true;
            }
        }

        return false;
    }
}
