<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Test\Authentication;

use Jalismrs\Stalactite\Client\Authentication\Model\TrustedApp;

/**
 * ModelFactory
 *
 * @package Jalismrs\Stalactite\Test\Authentication
 */
abstract class ModelFactory
{
    /**
     * getTestableTrustedApp
     *
     * @static
     * @return \Jalismrs\Stalactite\Client\Authentication\Model\TrustedApp
     */
    public static function getTestableTrustedApp() : TrustedApp
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
