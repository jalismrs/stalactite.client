<?php

namespace jalismrs\Stalactite\Client\Test\Authentication;

use jalismrs\Stalactite\Client\Authentication\Model\TrustedApp;

abstract class ModelFactory
{
    public static function getTestableTrustedApp(): TrustedApp
    {
        $trustedApp = new TrustedApp();
        $trustedApp->setName('fake name')
            ->setUid('azertyuiop')
            ->setGoogleOAuthClientId('qsdfghjklm')
            ->setAuthToken('aqwzsxedcrfv')
            ->setResetToken('tgbyhnujikol');

        return $trustedApp;
    }
}