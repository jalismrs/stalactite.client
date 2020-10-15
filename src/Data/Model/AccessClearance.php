<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Data\Model;

use hunomina\DataValidator\Rule\Json\JsonRule;
use Jalismrs\Stalactite\Client\AbstractModel;

/**
 * Class AccessClearance
 *
 * @package Jalismrs\Stalactite\Client\Data\Model
 */
class AccessClearance extends
    AbstractModel
{
    public const NO_ACCESS    = null;
    public const ADMIN_ACCESS = 'admin';
    public const USER_ACCESS  = 'user';
    
    /**
     * granted
     *
     * @var bool
     */
    private bool $granted;
    /**
     * type
     *
     * @var string|null
     */
    private ?string $type;
    
    /**
     * AccessClearance constructor.
     *
     * @param bool        $granted
     * @param string|null $type
     *
     * @codeCoverageIgnore
     */
    public function __construct(
        bool $granted = false,
        ?string $type = null
    ) {
        $this->granted = $granted;
        $this->type    = $type ?? self::NO_ACCESS;
    }
    
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
            'granted' => [
                'type' => JsonRule::BOOLEAN_TYPE,
            ],
            'type'    => [
                'type' => JsonRule::STRING_TYPE,
                'null' => true,
                'enum' => [
                    self::USER_ACCESS,
                    self::ADMIN_ACCESS,
                ],
            ],
        ];
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
     * hasUserAccessGranted
     *
     * @return bool
     */
    public function hasUserAccessGranted() : bool
    {
        return $this->isGranted()
            &&
            $this->type === self::USER_ACCESS;
    }
    
    /**
     * isGranted
     *
     * @return bool
     *
     * @codeCoverageIgnore
     */
    public function isGranted() : bool
    {
        return $this->granted;
    }
    
    /**
     * setGranted
     *
     * @param bool $granted
     *
     * @return $this
     *
     * @codeCoverageIgnore
     */
    public function setGranted(bool $granted) : self
    {
        $this->granted = $granted;
        
        return $this;
    }
    
    /**
     * hasAdminAccessGranted
     *
     * @return bool
     */
    public function hasAdminAccessGranted() : bool
    {
        return $this->isGranted()
            &&
            $this->type === self::ADMIN_ACCESS;
    }
}
