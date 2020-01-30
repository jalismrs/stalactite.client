<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Tests\Authentication;

use Jalismrs\Stalactite\Client\Authentication\Model\TrustedApp;

/**
 * ModelFactory
 *
 * @packageJalismrs\Stalactite\Service\Tests\Authentication
 */
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
