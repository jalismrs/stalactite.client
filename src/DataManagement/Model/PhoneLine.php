<?php
declare(strict_types = 1);

namespace jalismrs\Stalactite\Client\DataManagement\Model;

use jalismrs\Stalactite\Client\AbstractModel;

class PhoneLine extends AbstractModel
{
    /** @var null|PhoneType $type */
    private $type;

    /** @var null|string $value */
    private $value;

    /**
     * @return PhoneType|null
     */
    public function getType(): ?PhoneType
    {
        return $this->type;
    }

    /**
     * @param PhoneType|null $type
     * @return PhoneLine
     */
    public function setType(?PhoneType $type): PhoneLine
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getValue(): ?string
    {
        return $this->value;
    }

    /**
     * @param string|null $value
     * @return PhoneLine
     */
    public function setValue(?string $value): PhoneLine
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @return array
     */
    public function asArray(): array
    {
        return [
            'uid' => $this->uid,
            'value' => $this->value,
            'type' => $this->getType() ? $this->getType()->asArray() : null
        ];
    }
}
