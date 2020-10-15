<?php

namespace Jalismrs\Stalactite\Client\Tests\Authentication\Model;

use Jalismrs\Stalactite\Client\Authentication\Model\ClientApp;
use Jalismrs\Stalactite\Client\Authentication\Model\ServerApp;

/**
 * Class ModelFactory
 *
 * @package Jalismrs\Stalactite\Client\Tests\Authentication\Model
 */
class TestableModelFactory
{
    /**
     * getTestableClientApp
     *
     * @static
     * @return \Jalismrs\Stalactite\Client\Authentication\Model\ClientApp
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
    
    /**
     * getTestableServerApp
     *
     * @static
     * @return \Jalismrs\Stalactite\Client\Authentication\Model\ServerApp
     */
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
