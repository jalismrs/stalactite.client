<?php
declare(strict_types = 1);

namespace jalismrs\Stalactite\Client\DataManagement\Model;

use jalismrs\Stalactite\Client\AbstractModel;

class PhoneType extends AbstractModel
{
    /** @var null|string $name */
    private $name;

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     * @return PhoneType
     */
    public function setName(?string $name): PhoneType
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return array
     */
    public function asArray(): array
    {
        return [
            'uid' => $this->uid,
            'name' => $this->name
        ];
    }
}
