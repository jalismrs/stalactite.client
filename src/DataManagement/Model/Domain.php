<?php

namespace jalismrs\Stalactite\Client\DataManagement\Model;

use jalismrs\Stalactite\Client\AbstractModel;

class Domain extends AbstractModel
{
    /** @var null|string $uid */
    private $uid;

    /** @var null|string $name */
    private $name;

    /** @var null|string $type */
    private $type;

    /** @var null|string $apiKey */
    private $apiKey;

    /** @var null|string $externalAuth */
    private $externalAuth;

    /** @var null|string $generationDate */
    private $generationDate;

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     * @return Domain
     */
    public function setName(?string $name): Domain
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param string|null $type
     * @return Domain
     */
    public function setType(?string $type): Domain
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getApiKey(): ?string
    {
        return $this->apiKey;
    }

    /**
     * @param string|null $apiKey
     * @return Domain
     */
    public function setApiKey(?string $apiKey): Domain
    {
        $this->apiKey = $apiKey;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getExternalAuth(): ?string
    {
        return $this->externalAuth;
    }

    /**
     * @param string|null $externalAuth
     * @return Domain
     */
    public function setExternalAuth(?string $externalAuth): Domain
    {
        $this->externalAuth = $externalAuth;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getGenerationDate(): ?string
    {
        return $this->generationDate;
    }

    /**
     * @param string|null $generationDate
     * @return Domain
     */
    public function setGenerationDate(?string $generationDate): Domain
    {
        $this->generationDate = $generationDate;
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
            'type' => $this->type,
            'apiKey' => $this->apiKey,
            'externalAuth' => $this->externalAuth,
            'generationDate' => $this->generationDate
        ];
    }
}