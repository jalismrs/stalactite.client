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
     * createTrustedAppModel
     *
     * @static
     *
     * @param array $data
     *
     * @return \Jalismrs\Stalactite\Client\Authentication\Model\TrustedAppModel
     */
    public static function createTrustedAppModel(array $data) : TrustedAppModel
    {
        $model = new TrustedAppModel();
        $model
            ->setAuthToken($data['authToken'] ?? null)
            ->setGoogleOAuthClientId($data['googleOAuthClientId'] ?? null)
            ->setName($data['name'] ?? null)
            ->setResetToken($data['resetToken'] ?? null)
            ->setUid($data['uid'] ?? null);
        
        return $model;
    }
}
