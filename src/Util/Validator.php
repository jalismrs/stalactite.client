<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Util;

use hunomina\DataValidator\Data\Json\JsonData;
use hunomina\DataValidator\Exception\Json\InvalidDataException;
use hunomina\DataValidator\Exception\Json\InvalidSchemaException;
use hunomina\DataValidator\Schema\Json\JsonSchema;
use Jalismrs\Stalactite\Client\Exception\ValidatorException;

/**
 * Validator
 *
 * @package Jalismrs\Stalactite\Client\Util
 */
class Validator
{
    /**
     * @static
     * @var Validator|null
     */
    private static ?self $instance = null;
    /**
     * @var JsonData
     */
    private JsonData $data;
    /**
     * @var JsonSchema
     */
    private JsonSchema $schema;
    
    /**
     * Validator constructor.
     */
    private function __construct()
    {
        $this->data   = new JsonData();
        $this->schema = new JsonSchema();
    }
    
    /**
     * getInstance
     *
     * @static
     * @return static
     */
    public static function getInstance() : self
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
    public function getData() : ?array
    {
        return $this->data->getData();
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
    public function setData($data) : self
    {
        try {
            $this->data->setData($data);
        } catch (InvalidDataException $invalidDataException) {
            throw new ValidatorException(
                'Error while setting data',
                ValidatorException::INVALID_DATA_SET,
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
    public function setSchema(array $schema) : self
    {
        try {
            $this->schema->setSchema($schema);
        } catch (InvalidSchemaException $invalidSchemaException) {
            throw new ValidatorException(
                'Error while setting schema',
                ValidatorException::INVALID_SCHEMA,
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
    public function validate() : bool
    {
        try {
            return $this->schema->validate($this->data);
        } catch (InvalidDataException $invalidDataException) {
            throw new ValidatorException(
                'Invalid data',
                ValidatorException::INVALID_DATA,
                $invalidDataException
            );
        }
    }
}
