<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Data;

use hunomina\Validator\Json\Rule\JsonRule;

/**
 * Schema
 *
 * @package Jalismrs\Stalactite\Client\Data
 */
abstract class Schema
{
    public const CUSTOMER = [
        'uid' => [
            'type' => JsonRule::STRING_TYPE
        ],
        'email' => [
            'type' => JsonRule::STRING_TYPE
        ],
        'firstName' => [
            'type' => JsonRule::STRING_TYPE
        ],
        'lastName' => [
            'type' => JsonRule::STRING_TYPE
        ],
        'googleId' => [
            'type' => JsonRule::STRING_TYPE,
            'null' => true
        ]
    ];
    public const DOMAIN = [
        'uid' => [
            'type' => JsonRule::STRING_TYPE
        ],
        'name' => [
            'type' => JsonRule::STRING_TYPE
        ],
        'type' => [
            'type' => JsonRule::STRING_TYPE
        ],
        'apiKey' => [
            'type' => JsonRule::STRING_TYPE
        ],
        'externalAuth' => [
            'type' => JsonRule::BOOLEAN_TYPE
        ],
        'generationDate' => [
            'type' => JsonRule::STRING_TYPE,
            'date-format' => 'Y-m-d',
            'null' => true
        ]
    ];
    public const MINIMAL_USER = [
        'uid' => [
            'type' => JsonRule::STRING_TYPE
        ],
        'firstName' => [
            'type' => JsonRule::STRING_TYPE
        ],
        'lastName' => [
            'type' => JsonRule::STRING_TYPE
        ],
        'email' => [
            'type' => JsonRule::STRING_TYPE
        ],
        'gender' => [
            'type' => JsonRule::STRING_TYPE,
            'enum' => [
                'male',
                'female'
            ]
        ],
        'googleId' => [
            'type' => JsonRule::STRING_TYPE,
            'null' => true
        ],
        'location' => [
            'type' => JsonRule::STRING_TYPE,
            'null' => true
        ],
        'office' => [
            'type' => JsonRule::STRING_TYPE,
            'null' => true
        ],
        'admin' => [
            'type' => JsonRule::BOOLEAN_TYPE
        ],
        'birthday' => [
            'type' => JsonRule::STRING_TYPE,
            'date-format' => 'Y-m-d',
            'null' => true
        ]
    ];
    public const PHONE_LINE = [
        'uid' => [
            'type' => JsonRule::STRING_TYPE
        ],
        'value' => [
            'type' => JsonRule::STRING_TYPE
        ],
        'type' => [
            'type' => JsonRule::OBJECT_TYPE,
            'schema' => self::PHONE_TYPE
        ]
    ];
    public const PHONE_TYPE = [
        'uid' => [
            'type' => JsonRule::STRING_TYPE
        ],
        'name' => [
            'type' => JsonRule::STRING_TYPE
        ]
    ];
    public const POST = [
        'uid' => [
            'type' => JsonRule::STRING_TYPE
        ],
        'name' => [
            'type' => JsonRule::STRING_TYPE
        ],
        'shortName' => [
            'type' => JsonRule::STRING_TYPE
        ],
        'adminAccess' => [
            'type' => JsonRule::BOOLEAN_TYPE
        ],
        'allowAccess' => [
            'type' => JsonRule::BOOLEAN_TYPE
        ]
    ];
    public const USER = [
        'uid' => [
            'type' => JsonRule::STRING_TYPE
        ],
        'firstName' => [
            'type' => JsonRule::STRING_TYPE
        ],
        'lastName' => [
            'type' => JsonRule::STRING_TYPE
        ],
        'email' => [
            'type' => JsonRule::STRING_TYPE
        ],
        'gender' => [
            'type' => JsonRule::STRING_TYPE,
            'enum' => [
                'male',
                'female'
            ]
        ],
        'googleId' => [
            'type' => JsonRule::STRING_TYPE,
            'null' => true
        ],
        'location' => [
            'type' => JsonRule::STRING_TYPE,
            'null' => true
        ],
        'office' => [
            'type' => JsonRule::STRING_TYPE,
            'null' => true
        ],
        'admin' => [
            'type' => JsonRule::BOOLEAN_TYPE
        ],
        'birthday' => [
            'type' => JsonRule::STRING_TYPE,
            'date-format' => 'Y-m-d',
            'null' => true
        ],
        'posts' => [
            'type' => JsonRule::LIST_TYPE,
            'schema' => self::POST
        ],
        'leads' => [
            'type' => JsonRule::LIST_TYPE,
            'schema' => self::POST
        ],
        'phoneLines' => [
            'type' => JsonRule::LIST_TYPE,
            'schema' => self::PHONE_LINE
        ]
    ];
}
