<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Authentication;

use hunomina\Validator\Json\Rule\JsonRule;

/**
 * Schema
 *
 * @package Jalismrs\Stalactite\Service\Authentication
 */
abstract class Schema
{
    public const TRUSTED_APP      = [
        'uid'                 => [
            'type' => JsonRule::STRING_TYPE
        ],
        'name'                => [
            'type' => JsonRule::STRING_TYPE
        ],
        'authToken'           => [
            'type' => JsonRule::STRING_TYPE
        ],
        'googleOAuthClientId' => [
            'type' => JsonRule::STRING_TYPE
        ]
    ];
    
    public const TRUSTED_APP_FULL = [
        'uid'                 => [
            'type' => JsonRule::STRING_TYPE
        ],
        'name'                => [
            'type' => JsonRule::STRING_TYPE
        ],
        'authToken'           => [
            'type' => JsonRule::STRING_TYPE
        ],
        'googleOAuthClientId' => [
            'type' => JsonRule::STRING_TYPE
        ],
        'resetToken'          => [
            'type' => JsonRule::STRING_TYPE
        ]
    ];
}
