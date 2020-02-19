<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Util;

use hunomina\Validator\Json\Data\JsonData;
use hunomina\Validator\Json\Exception\InvalidDataException;
use hunomina\Validator\Json\Exception\InvalidDataTypeException;
use hunomina\Validator\Json\Exception\InvalidSchemaException;
use hunomina\Validator\Json\Schema\JsonSchema;
use Jalismrs\Stalactite\Client\Exception\ValidatorException;

/**
 * Validator
 *
 * @package Jalismrs\Stalactite\Client\Util
 */
class Validator
{
    /**
     * @var self
     */
    private static $instance;
    /**
     * @var JsonData
     */
    private $container;
    /**
     * @var JsonSchema
     */
    private $validator;

    /**
     * Validator constructor.
     */
    private function __construct()
    {
        $this->container = new JsonData();
        $this->validator = new JsonSchema();
    }

    /**
     * getInstance
     *
     * @static
     * @return static
     */
    public static function getInstance(): self
    {
        if (!(self::$instance instanceof self)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * getData
     *
     * @return array|null
     */
    public function getData(): ?array
    {
        return $this->container->getData();
    }

    /**
     * setData
     *
     * @param $data
     *
     * @return $this
     *
     * @throws ValidatorException
     */
    public function setData($data): self
    {
        try {
            $this->container->setData($data);
        } catch (InvalidDataException $invalidDataException) {
            throw new ValidatorException(
                'Error while setting data',
                $invalidDataException->getCode(),
                $invalidDataException
            );
        }

        return $this;
    }

    /**
     * setSchema
     *
     * @param array $schema
     *
     * @return $this
     *
     * @throws ValidatorException
     */
    public function setSchema(array $schema): self
    {
        try {
            $this->validator->setSchema($schema);
        } catch (InvalidSchemaException $invalidSchemaException) {
            throw new ValidatorException(
                'Error while setting schema',
                $invalidSchemaException->getCode(),
                $invalidSchemaException
            );
        }

        return $this;
    }

    /**
     * validate
     *
     * @return bool
     *
     * @throws ValidatorException
     */
    public function validate(): bool
    {
        try {
            return $this->validator->validate($this->container);
        } catch (InvalidDataTypeException $invalidDataTypeException) {
            throw new ValidatorException(
                'Error while validating',
                $invalidDataTypeException->getCode(),
                $invalidDataTypeException
            );
        }
    }

    /**
     * getLastError
     *
     * @return string|null
     */
    public function getLastError(): ?string
    {
        return $this->validator->getLastError();
    }
}
