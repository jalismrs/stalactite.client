<?php

namespace jalismrs\Stalactite\Client\Test\Authentication;

use jalismrs\Stalactite\Client\Authentication\Model\TrustedApp;

abstract class ModelFactory
{
    public static function getTestableTrustedApp(): TrustedApp
    {
        $trustedApp = new TrustedApp();
        $trustedApp->setName('fake name')
            ->setGoogleOAuthClientId('qsdfghjklm')
            ->setAuthToken('aqwzsxedcrfv')
            ->setResetToken('tgbyhnujikol')
            ->setUid('azertyuiop');

        return $trustedApp;
    }
}