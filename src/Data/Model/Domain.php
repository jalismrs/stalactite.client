<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Data\Model;

use Jalismrs\Stalactite\Client\AbstractModel;

/**
 * Domain
 *
 * @package Jalismrs\Stalactite\Service\Data\Model
 */
class Domain extends
    AbstractModel
{
    /**
     * @var null|string
     */
    private $name;
    /**
     * @var null|string
     */
    private $type;
    /**
     * @var null|string
     */
    private $apiKey;
    /**
     * @var bool null|string
     */
    private $externalAuth = false;
    /**
     * @var null|string
     */
    private $generationDate;

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
     * getType
     *
     * @return null|string
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * setType
     *
     * @param null|string $type
     *
     * @return $this
     */
    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * getApiKey
     *
     * @return null|string
     */
    public function getApiKey(): ?string
    {
        return $this->apiKey;
    }

    /**
     * setApiKey
     *
     * @param null|string $apiKey
     *
     * @return $this
     */
    public function setApiKey(?string $apiKey): self
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    /**
     * hasExternalAuth
     *
     * @return bool
     */
    public function hasExternalAuth(): bool
    {
        return $this->externalAuth;
    }

    /**
     * setExternalAuth
     *
     * @param bool $externalAuth
     *
     * @return $this
     */
    public function setExternalAuth(bool $externalAuth): self
    {
        $this->externalAuth = $externalAuth;

        return $this;
    }

    /**
     * getGenerationDate
     *
     * @return null|string
     */
    public function getGenerationDate(): ?string
    {
        return $this->generationDate;
    }

    /**
     * setGenerationDate
     *
     * @param null|string $generationDate
     *
     * @return $this
     */
    public function setGenerationDate(?string $generationDate): self
    {
        $this->generationDate = $generationDate;

        return $this;
    }
}
