<?php
declare(strict_types=1);

namespace Test\Authentication;

use Jalismrs\Stalactite\Client\Authentication\Model\TrustedAppModel;

/**
 * ModelFactory
 *
 * @package Test\Authentication
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
