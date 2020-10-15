<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Authentication\Model;

use hunomina\DataValidator\Rule\Json\JsonRule;
use Jalismrs\Stalactite\Client\AbstractModel;

/**
 * Class ServerApp
 *
 * @package Jalismrs\Stalactite\Client\Authentication\Model
 */
class ServerApp extends
    AbstractModel
{
    /**
     * name
     *
     * @var string|null
     */
    private ?string $name = null;
    /**
     * tokenSignatureKey
     *
     * @var string|null
     */
    private ?string $tokenSignatureKey = null;
    
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
            'uid'               => [
                'type' => JsonRule::STRING_TYPE,
            ],
            'name'              => [
                'type' => JsonRule::STRING_TYPE,
            ],
            'tokenSignatureKey' => [
                'type' => JsonRule::STRING_TYPE,
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
    public function setName(?string $name) : ServerApp
    {
        $this->name = $name;
        
        return $this;
    }
    
    /**
     * getTokenSignatureKey
     *
     * @return string|null
     *
     * @codeCoverageIgnore
     */
    public function getTokenSignatureKey() : ?string
    {
        return $this->tokenSignatureKey;
    }
    
    /**
     * setTokenSignatureKey
     *
     * @param string|null $tokenSignatureKey
     *
     * @return $this
     *
     * @codeCoverageIgnore
     */
    public function setTokenSignatureKey(?string $tokenSignatureKey) : ServerApp
    {
        $this->tokenSignatureKey = $tokenSignatureKey;
        
        return $this;
    }
}
