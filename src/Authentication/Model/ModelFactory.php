<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Authentication\Model;

/**
 * Class ModelFactory
 *
 * @package Jalismrs\Stalactite\Client\Authentication\Model
 * Factory to instantiate models from arrays
 */
abstract class ModelFactory
{
    /**
     * createTrustedApp
     *
     * @static
     *
     * @param array $data
     *
     * @return \Jalismrs\Stalactite\Client\Authentication\Model\TrustedAppModel
     */
    public static function createTrustedApp(array $data) : TrustedAppModel
    {
        $model = new TrustedAppModel();
        $model
            ->setName($data['name'] ?? null)
            ->setResetToken($data['resetToken'] ?? null)
            ->setAuthToken($data['authToken'] ?? null)
            ->setGoogleOAuthClientId($data['googleOAuthClientId'] ?? null)
            ->setUid($data['uid'] ?? null);
        
        return $model;
    }
}
