<?php

namespace Jalismrs\Stalactite\Client\Data\Model;

use hunomina\DataValidator\Rule\Json\JsonRule;
use Jalismrs\Stalactite\Client\AbstractModel;

class Permission extends
    AbstractModel
{
    private const SEPARATOR = '.';

    /**
     * scope
     *
     * @var string|null
     */
    private ?string $scope;
    /**
     * resource
     *
     * @var string|null
     */
    private ?string $resource;
    /**
     * operation
     *
     * @var string|null
     */
    private ?string $operation;

    /**
     * Permission constructor.
     *
     * @param string|null $scope
     * @param string|null $resource
     * @param string|null $operation
     *
     * @codeCoverageIgnore
     */
    public function __construct(
        ?string $scope = null,
        ?string $resource = null,
        ?string $operation = null
    )
    {
        $this->scope = $scope;
        $this->resource = $resource;
        $this->operation = $operation;
    }

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
            'uid' => ['type' => JsonRule::STRING_TYPE],
            'scope' => ['type' => JsonRule::STRING_TYPE],
            'resource' => ['type' => JsonRule::STRING_TYPE],
            'operation' => ['type' => JsonRule::STRING_TYPE],
        ];
    }

    /**
     * getScope
     *
     * @return string|null
     *
     * @codeCoverageIgnore
     */
    public function getScope(): ?string
    {
        return $this->scope;
    }

    /**
     * setScope
     *
     * @param string|null $scope
     *
     * @return $this
     *
     * @codeCoverageIgnore
     */
    public function setScope(?string $scope): self
    {
        $this->scope = $scope;

        return $this;
    }

    /**
     * getResource
     *
     * @return string|null
     *
     * @codeCoverageIgnore
     */
    public function getResource(): ?string
    {
        return $this->resource;
    }

    /**
     * setResource
     *
     * @param string|null $resource
     *
     * @return $this
     *
     * @codeCoverageIgnore
     */
    public function setResource(?string $resource): self
    {
        $this->resource = $resource;

        return $this;
    }

    /**
     * getOperation
     *
     * @return string|null
     *
     * @codeCoverageIgnore
     */
    public function getOperation(): ?string
    {
        return $this->operation;
    }

    /**
     * setOperation
     *
     * @param string|null $operation
     *
     * @return $this
     *
     * @codeCoverageIgnore
     */
    public function setOperation(?string $operation): self
    {
        $this->operation = $operation;

        return $this;
    }

    /**
     * __toString
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->scope . self::SEPARATOR . $this->resource . self::SEPARATOR . $this->operation;
    }
}
