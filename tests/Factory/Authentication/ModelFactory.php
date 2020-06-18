<?php

namespace Jalismrs\Stalactite\Client\Tests\Factory\Authentication;

use Jalismrs\Stalactite\Client\Authentication\Model\TrustedApp;

abstract class ModelFactory
{
    /**
     * getTestableTrustedApp
     *
     * @static
     * @return TrustedApp
     */
    public static function getTestableTrustedApp(): TrustedApp
    {
        $model = new TrustedApp();
        $model
            ->setName('fake name')
            ->setGoogleOAuthClientId('qsdfghjklm')
            ->setAuthToken('aqwzsxedcrfv')
            ->setResetToken('tgbyhnujikol')
            ->setUid('azertyuiop');

        return $model;
    }
}