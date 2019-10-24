<?php

namespace jalismrs\Stalactite\Client\AccessManagement;

use hunomina\Validator\Json\Rule\JsonRule;

abstract class Schema
{
    public const ACCESS_CLEARANCE = [
        'accessGranted' => ['type' => JsonRule::BOOLEAN_TYPE],
        'accessType' => ['type' => JsonRule::STRING_TYPE, 'null' => true, 'enum' => ['user', 'admin']]
    ];
}