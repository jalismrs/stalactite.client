<?php

namespace Jalismrs\Stalactite\Client\Tests\Factory\Authentication;

use Jalismrs\Stalactite\Client\Authentication\Model\ClientApp;
use Jalismrs\Stalactite\Client\Authentication\Model\ServerApp;

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

    public static function getTestableServerApp(): ServerApp
    {
        $model = new ServerApp();
        $model
            ->setName('fake name')
            ->setTokenSignatureKey('qsdfghjklm')
            ->setUid('azertyuiop');

        return $model;
    }
}