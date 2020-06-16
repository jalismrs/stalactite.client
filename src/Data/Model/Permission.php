<?php

namespace Jalismrs\Stalactite\Client\Data\Model;

use Jalismrs\Stalactite\Client\AbstractModel;

class Permission extends AbstractModel
{
    private const SEPARATOR = '.';

    private ?string $scope = null;
    private ?string $resource = null;
    private ?string $operation = null;

    /**
     * @return string|null
     */
    public function getScope(): ?string
    {
        return $this->scope;
    }

    /**
     * @param string|null $scope
     * @return Permission
     */
    public function setScope(?string $scope): self
    {
        $this->scope = $scope;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getResource(): ?string
    {
        return $this->resource;
    }

    /**
     * @param string|null $resource
     * @return Permission
     */
    public function setResource(?string $resource): self
    {
        $this->resource = $resource;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getOperation(): ?string
    {
        return $this->operation;
    }

    /**
     * @param string|null $operation
     * @return Permission
     */
    public function setOperation(?string $operation): self
    {
        $this->operation = $operation;
        return $this;
    }

    public function __toString(): string
    {
        return $this->scope . self::SEPARATOR . $this->resource . self::SEPARATOR . $this->operation;
    }
}