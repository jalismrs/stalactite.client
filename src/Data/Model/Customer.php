<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Data\Model;

use hunomina\DataValidator\Rule\Json\JsonRule;
use Jalismrs\Stalactite\Client\AbstractModel;

/**
 * Customer
 *
 * @package Jalismrs\Stalactite\Service\Data\Model
 */
class Customer extends
    AbstractModel
{
    use ModelNameTrait;
    
    /**
     * googleId
     *
     * @var string|null
     */
    private ?string $googleId = null;
    /**
     * email
     *
     * @var string|null
     */
    private ?string $email = null;

    /**
     * getSchema
     *
     * @static
     * @return array[]
     *
     * @codeCoverageIgnore
     */
    public static function getSchema(): array
    {
        return [
            'uid' => [
                'type' => JsonRule::STRING_TYPE,
            ],
            'email' => [
                'type' => JsonRule::STRING_TYPE,
            ],
            'firstName' => [
                'type' => JsonRule::STRING_TYPE,
            ],
            'lastName' => [
                'type' => JsonRule::STRING_TYPE,
            ],
            'googleId' => [
                'type' => JsonRule::STRING_TYPE,
                'null' => true,
            ],
        ];
    }

    /**
     * getGoogleId
     *
     * @return string|null
     *
     * @codeCoverageIgnore
     */
    public function getGoogleId(): ?string
    {
        return $this->googleId;
    }

    /**
     * setGoogleId
     *
     * @param string|null $googleId
     *
     * @return $this
     *
     * @codeCoverageIgnore
     */
    public function setGoogleId(?string $googleId): self
    {
        $this->googleId = $googleId;

        return $this;
    }

    /**
     * getEmail
     *
     * @return string|null
     *
     * @codeCoverageIgnore
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * setEmail
     *
     * @param string|null $email
     *
     * @return $this
     *
     * @codeCoverageIgnore
     */
    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }
}
