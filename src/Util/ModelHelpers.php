<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Util;

use Jalismrs\Stalactite\Client\AbstractModel;
use Jalismrs\Stalactite\Client\ClientException;
use function get_class;
use function gettype;
use function is_a;
use function is_object;

/**
 * ModelHelpers
 *
 * @package Jalismrs\Stalactite\Client\Util
 */
final class ModelHelpers
{
    /**
     * getUid
     *
     * @static
     *
     * @param             $model
     * @param string|null $class
     *
     * @return null|string
     *
     * @throws \Jalismrs\Stalactite\Client\ClientException
     */
    public static function getUid($model, string $class = null) : ?string
    {
        if (!$model instanceof AbstractModel) {
            $actual = is_object($model)
                ? get_class($model)
                : gettype($model);
            
            throw new ClientException(
                "parameter must be a model, {$actual} given",
                ClientException::INVALID_PARAMETER_PASSED_TO_CLIENT
            );
        }
        
        $expected = $class ?? AbstractModel::class;
        if (!is_a($model, $expected)) {
            $actual = get_class($model);
            
            throw new ClientException(
                "model parameter must be a {$expected} model, {$actual} given",
                ClientException::INVALID_PARAMETER_PASSED_TO_CLIENT
            );
        }
        
        return $model->getUid();
    }
    
    /**
     * getUids
     *
     * @static
     *
     * @param array       $models
     * @param string|null $class
     *
     * @return array
     *
     * @throws \Jalismrs\Stalactite\Client\ClientException
     */
    public static function getUids(array $models, string $class = null) : array
    {
        $uids = [];
        
        foreach ($models as $model) {
            $uids[] = self::getUid($model, $class);
        }
        
        return $uids;
    }
}
