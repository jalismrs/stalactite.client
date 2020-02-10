<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Access;

use hunomina\Validator\Json\Rule\JsonRule;
use Jalismrs\Stalactite\Client\Access\Model\AccessClearance;
use Jalismrs\Stalactite\Client\Data\Schema as DataSchema;

/**
 * Schema
 *
 * @package Jalismrs\Stalactite\Service\Access
 */
abstract class Schema
{
    public const ACCESS_CLEARANCE = [
        'granted' => [
            'type' => JsonRule::BOOLEAN_TYPE
        ],
        'type' => [
            'type' => JsonRule::STRING_TYPE,
            'null' => true,
            'enum' => [
                AccessClearance::USER_ACCESS,
                AccessClearance::ADMIN_ACCESS
            ]
        ]
    ];

    public const DOMAIN_CUSTOMER_RELATION = [
        'uid' => [
            'type' => JsonRule::STRING_TYPE
        ],
        'domain' => [
            'type' => JsonRule::OBJECT_TYPE,
            'schema' => DataSchema::DOMAIN
        ],
        'customer' => [
            'type' => JsonRule::OBJECT_TYPE,
            'schema' => DataSchema::CUSTOMER
        ]
    ];

    public const DOMAIN_USER_RELATION = [
        'uid' => [
            'type' => JsonRule::STRING_TYPE
        ],
        'domain' => [
            'type' => JsonRule::OBJECT_TYPE,
            'schema' => DataSchema::DOMAIN
        ],
        'user' => [
            'type' => JsonRule::OBJECT_TYPE,
            'schema' => DataSchema::USER
        ]
    ];
}
