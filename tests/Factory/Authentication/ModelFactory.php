<?php

namespace Jalismrs\Stalactite\Client\Tests\Factory\Authentication;

use Jalismrs\Stalactite\Client\Authentication\Model\ClientApp;

abstract class ModelFactory
{
    /**
     * @return ClientApp
     */
    public static function getTestableClientApp(): ClientApp
    {
        $model = new ClientApp();
        $model
            ->setName('fake name')
            ->setGoogleOAuthClientId('qsdfghjklm')
            ->setUid('azertyuiop');

        return $model;
    }
}