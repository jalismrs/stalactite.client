<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Authentication\Model;

/**
 * Class ModelFactory
 *
 * @package Jalismrs\Stalactite\Client\Authentication\Model
 */
class ModelFactory
{
    /**
     * createClientApp
     *
     * @static
     *
     * @param array $data
     *
     * @return \Jalismrs\Stalactite\Client\Authentication\Model\ClientApp
     */
    public static function createClientApp(array $data): ClientApp
    {
        $model = new ClientApp();
        $model
            ->setGoogleOAuthClientId($data['googleOAuthClientId'] ?? null)
            ->setName($data['name'] ?? null)
            ->setUid($data['uid'] ?? null);

        return $model;
    }
    
    /**
     * createServerApp
     *
     * @static
     *
     * @param array $data
     *
     * @return \Jalismrs\Stalactite\Client\Authentication\Model\ServerApp
     */
    public static function createServerApp(array $data): ServerApp
    {
        $model = new ServerApp();
        $model
            ->setTokenSignatureKey($data['tokenSignatureKey'] ?? null)
            ->setName($data['name'] ?? null)
            ->setUid($data['uid'] ?? null);

        return $model;
    }
}
