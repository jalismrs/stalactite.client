<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Tests\Authentication;

use Jalismrs\Stalactite\Client\Authentication\Model\TrustedAppModel;

/**
 * ModelFactory
 *
 * @packageJalismrs\Stalactite\Client\Tests\Authentication
 */
abstract class ModelFactory
{
    /**
     * getTestableTrustedApp
     *
     * @static
     * @return TrustedAppModel
     */
    public static function getTestableTrustedApp(): TrustedAppModel
    {
        $model = new TrustedAppModel();
        $model
            ->setName('fake name')
            ->setGoogleOAuthClientId('qsdfghjklm')
            ->setAuthToken('aqwzsxedcrfv')
            ->setResetToken('tgbyhnujikol')
            ->setUid('azertyuiop');

        return $model;
    }
}
