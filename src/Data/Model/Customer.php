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
            'uid' => ['type' => JsonRule::STRING_TYPE],
            'email' => ['type' => JsonRule::STRING_TYPE],
            'firstName' => ['type' => JsonRule::STRING_TYPE],
            'lastName' => ['type' => JsonRule::STRING_TYPE]
        ];
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
