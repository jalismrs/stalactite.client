<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Data\Model;

use hunomina\DataValidator\Rule\Json\JsonRule;
use Jalismrs\Stalactite\Client\AbstractModel;

/**
 * Class Domain
 *
 * @package Jalismrs\Stalactite\Client\Data\Model
 */
class Domain extends
    AbstractModel
{
    /**
     * name
     *
     * @var string|null
     */
    private ?string $name = null;
    /**
     * type
     *
     * @var string|null
     */
    private ?string $type = null;
    /**
     * apiKey
     *
     * @var string|null
     */
    private ?string $apiKey = null;
    /**
     * externalAuth
     *
     * @var bool
     */
    private bool $externalAuth = false;
    /**
     * generationDate
     *
     * @var string|null
     */
    private ?string $generationDate = null;
    
    /**
     * getSchema
     *
     * @static
     * @return array[]
     *
     * @codeCoverageIgnore
     */
    public static function getSchema() : array
    {
        return [
            'uid'            => [
                'type' => JsonRule::STRING_TYPE,
            ],
            'name'           => [
                'type' => JsonRule::STRING_TYPE,
            ],
            'type'           => [
                'type' => JsonRule::STRING_TYPE,
            ],
            'apiKey'         => [
                'type' => JsonRule::STRING_TYPE,
            ],
            'externalAuth'   => [
                'type' => JsonRule::BOOLEAN_TYPE,
            ],
            'generationDate' => [
                'type'        => JsonRule::STRING_TYPE,
                'date-format' => 'Y-m-d',
                'null'        => true,
            ],
        ];
    }
    
    /**
     * getName
     *
     * @return string|null
     *
     * @codeCoverageIgnore
     */
    public function getName() : ?string
    {
        return $this->name;
    }
    
    /**
     * setName
     *
     * @param string|null $name
     *
     * @return $this
     *
     * @codeCoverageIgnore
     */
    public function setName(?string $name) : self
    {
        $this->name = $name;
        
        return $this;
    }
    
    /**
     * getType
     *
     * @return string|null
     *
     * @codeCoverageIgnore
     */
    public function getType() : ?string
    {
        return $this->type;
    }
    
    /**
     * setType
     *
     * @param string|null $type
     *
     * @return $this
     *
     * @codeCoverageIgnore
     */
    public function setType(?string $type) : self
    {
        $this->type = $type;
        
        return $this;
    }
    
    /**
     * getApiKey
     *
     * @return string|null
     *
     * @codeCoverageIgnore
     */
    public function getApiKey() : ?string
    {
        return $this->apiKey;
    }
    
    /**
     * setApiKey
     *
     * @param string|null $apiKey
     *
     * @return $this
     *
     * @codeCoverageIgnore
     */
    public function setApiKey(?string $apiKey) : self
    {
        $this->apiKey = $apiKey;
        
        return $this;
    }
    
    /**
     * hasExternalAuth
     *
     * @return bool
     *
     * @codeCoverageIgnore
     */
    public function hasExternalAuth() : bool
    {
        return $this->externalAuth;
    }
    
    /**
     * setExternalAuth
     *
     * @param bool $externalAuth
     *
     * @return $this
     *
     * @codeCoverageIgnore
     */
    public function setExternalAuth(bool $externalAuth) : self
    {
        $this->externalAuth = $externalAuth;
        
        return $this;
    }
    
    /**
     * getGenerationDate
     *
     * @return string|null
     *
     * @codeCoverageIgnore
     */
    public function getGenerationDate() : ?string
    {
        return $this->generationDate;
    }
    
    /**
     * setGenerationDate
     *
     * @param string|null $generationDate
     *
     * @return $this
     *
     * @codeCoverageIgnore
     */
    public function setGenerationDate(?string $generationDate) : self
    {
        $this->generationDate = $generationDate;
        
        return $this;
    }
}
