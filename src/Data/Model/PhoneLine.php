<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Data\Model;

use Jalismrs\Stalactite\Client\AbstractModel;

/**
 * PhoneLine
 *
 * @package Jalismrs\Stalactite\Client\Data\Model
 */
class PhoneLine extends
    AbstractModel
{
    /**
     * @var null|PhoneType
     */
    private $type;
    /**
     * @var null|string
     */
    private $value;

    /**
     * getType
     *
     * @return null|PhoneType
     */
    public function getType(): ?PhoneType
    {
        return $this->type;
    }

    /**
     * setType
     *
     * @param null|PhoneType $type
     *
     * @return $this
     */
    public function setType(?PhoneType $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * getValue
     *
     * @return null|string
     */
    public function getValue(): ?string
    {
        return $this->value;
    }

    /**
     * setValue
     *
     * @param null|string $value
     *
     * @return $this
     */
    public function setValue(?string $value): self
    {
        $this->value = $value;

        return $this;
    }

    /**
     * asArray
     *
     * @return array
     */
    public function asArray(): array
    {
        return [
            'uid' => $this->uid,
            'value' => $this->value,
            'type' => null === $this->type
                ? null
                : $this->type->asArray(),
        ];
    }
}
