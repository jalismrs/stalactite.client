<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Authentication\Model;

use hunomina\DataValidator\Rule\Json\JsonRule;
use Jalismrs\Stalactite\Client\AbstractModel;

/**
 * Class ClientApp
 *
 * @package Jalismrs\Stalactite\Client\Authentication\Model
 */
class ClientApp extends
    AbstractModel
{
    /**
     * name
     *
     * @var string|null
     */
    private ?string $name = null;
    /**
     * googleOAuthClientId
     *
     * @var string|null
     */
    private ?string $googleOAuthClientId = null;
    
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
            'uid'                 => [
                'type' => JsonRule::STRING_TYPE,
            ],
            'name'                => [
                'type' => JsonRule::STRING_TYPE,
            ],
            'googleOAuthClientId' => [
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
    public function setName(?string $name) : self
    {
        $this->name = $name;
        
        return $this;
    }
    
    /**
     * getGoogleOAuthClientId
     *
     * @return string|null
     *
     * @codeCoverageIgnore
     */
    public function getGoogleOAuthClientId() : ?string
    {
        return $this->googleOAuthClientId;
    }
    
    /**
     * setGoogleOAuthClientId
     *
     * @param string|null $googleOAuthClientId
     *
     * @return $this
     *
     * @codeCoverageIgnore
     */
    public function setGoogleOAuthClientId(?string $googleOAuthClientId) : self
    {
        $this->googleOAuthClientId = $googleOAuthClientId;
        
        return $this;
    }
}
