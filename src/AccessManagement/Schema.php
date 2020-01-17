<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\AccessManagement;

use hunomina\Validator\Json\Rule\JsonRule;
use Jalismrs\Stalactite\Client\DataManagement\Schema as DataManagementSchema;

/**
 * Schema
 *
 * @package Jalismrs\Stalactite\Client\AccessManagement
 */
abstract class Schema
{
    public const ACCESS_CLEARANCE = [
        'accessGranted' => [
            'type' => JsonRule::BOOLEAN_TYPE
        ],
        'accessType'    => [
            'type' => JsonRule::STRING_TYPE,
            'null' => true,
            'enum' => [
                'user',
                'admin'
            ]
        ]
    ];
    
    public const DOMAIN_CUSTOMER_RELATION = [
        'uid'      => [
            'type' => JsonRule::STRING_TYPE
        ],
        'domain'   => [
            'type'   => JsonRule::OBJECT_TYPE,
            'schema' => DataManagementSchema::DOMAIN
        ],
        'customer' => [
            'type'   => JsonRule::OBJECT_TYPE,
            'schema' => DataManagementSchema::CUSTOMER
        ]
    ];
    
    public const DOMAIN_USER_RELATION = [
        'uid'    => [
            'type' => JsonRule::STRING_TYPE
        ],
        'domain' => [
            'type'   => JsonRule::OBJECT_TYPE,
            'schema' => DataManagementSchema::DOMAIN
        ],
        'user'   => [
            'type'   => JsonRule::OBJECT_TYPE,
            'schema' => DataManagementSchema::MINIMAL_USER
        ]
    ];
}
