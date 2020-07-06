<?php

namespace Jalismrs\Stalactite\Client\Data\Model;

use hunomina\DataValidator\Rule\Json\JsonRule;
use Jalismrs\Stalactite\Client\AbstractModel;

class Permission extends AbstractModel
{
    private const SEPARATOR = '.';

    private ?string $scope;
    private ?string $resource;
    private ?string $operation;

    public function __construct(?string $scope = null, ?string $resource = null, ?string $operation = null)
    {
        $this->scope = $scope;
        $this->resource = $resource;
        $this->operation = $operation;
    }

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

    public static function getSchema(): array
    {
        return [
            'uid' => ['type' => JsonRule::STRING_TYPE],
            'scope' => ['type' => JsonRule::STRING_TYPE],
            'resource' => ['type' => JsonRule::STRING_TYPE],
            'operation' => ['type' => JsonRule::STRING_TYPE]
        ];
    }
}