<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Test\Authentication;

use Jalismrs\Stalactite\Client\Authentication\Model\TrustedAppModel;

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
     * @return \Jalismrs\Stalactite\Client\Authentication\Model\TrustedAppModel
     */
    public static function getTestableTrustedApp() : TrustedAppModel
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
