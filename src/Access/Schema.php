<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Access;

use hunomina\Validator\Json\Rule\JsonRule;
use Jalismrs\Stalactite\Client\Data\Schema as DataSchema;

/**
 * Schema
 *
 * @package Jalismrs\Stalactite\Client\Access
 */
abstract class Schema
{
    public const ACCESS_CLEARANCE = [
        'accessGranted' => [
            'type' => JsonRule::BOOLEAN_TYPE
        ],
        'accessType' => [
            'type' => JsonRule::STRING_TYPE,
            'null' => true,
            'enum' => [
                'user',
                'admin'
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
            'schema' => DataSchema::MINIMAL_USER
        ]
    ];
}
