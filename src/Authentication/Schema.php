<?php

namespace jalismrs\Stalactite\Client\Authentication;

use hunomina\Validator\Json\Rule\JsonRule;

abstract class Schema
{
    public const TRUSTED_APP = [
        'uid' => ['type' => JsonRule::STRING_TYPE],
        'name' => ['type' => JsonRule::STRING_TYPE],
        'authToken' => ['type' => JsonRule::STRING_TYPE],
        'googleOAuthClientId' => ['type' => JsonRule::STRING_TYPE]
    ];
}