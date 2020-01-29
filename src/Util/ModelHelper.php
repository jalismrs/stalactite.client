<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Util;

use InvalidArgumentException;
use Jalismrs\Stalactite\Client\AbstractModel;
use function get_class;
use function gettype;
use function is_a;
use function is_object;

/**
 * ModelHelper
 *
 * @package Jalismrs\Stalactite\Client\Util
 */
final class ModelHelper
{
    /**
     * getUids
     *
     * @static
     *
     * @param array $models
     * @param string|null $class
     * @return array
     * @throws InvalidArgumentException
     */
    public static function getUids(array $models, string $class = null): array
    {
        $uids = [];

        foreach ($models as $model) {
            if ($model instanceof AbstractModel && ($class ? is_a($model, $class) : true)) {
                $uids[] = $model->getUid();
            } else {
                $actual = is_object($model) ? get_class($model) : gettype($model);
                throw new InvalidArgumentException(
                    'model parameter must be a ' . ($class ?: AbstractModel::class) . ' model, ' . $actual . ' given'
                );
            }
        }

        return $uids;
    }
}
