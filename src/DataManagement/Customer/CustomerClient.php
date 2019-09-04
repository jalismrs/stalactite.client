<?php

namespace jalismrs\Stalactite\Client\DataManagement\Customer;

use jalismrs\Stalactite\Client\AbstractClient;
use jalismrs\Stalactite\Client\DataManagement\Client;

class CustomerClient extends AbstractClient
{
    public const API_URL_PREFIX = Client::API_URL_PREFIX . '/customers';
}