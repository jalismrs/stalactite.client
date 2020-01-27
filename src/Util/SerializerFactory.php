<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Util;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\XmlFileLoader;
use function Jalismrs\Stalactite\Client\path;

/**
 * SerializerFactory
 *
 * @package Jalismrs\Stalactite\Client\Util
 */
final class SerializerFactory
{
    /**
     * create
     *
     * @static
     * @return \Symfony\Component\Serializer\Serializer
     *
     * @throws \Symfony\Component\Serializer\Exception\InvalidArgumentException
     * @throws \Symfony\Component\Serializer\Exception\LogicException
     * @throws \Symfony\Component\Serializer\Exception\MappingException
     */
    public static function create() : Serializer
    {
        static $serializer = null;
        
        if (null === $serializer) {
            $serializer = new Serializer(
                [
                    new ObjectNormalizer(
                        new ClassMetadataFactory(
                            new XmlFileLoader(
                                path('config/serialization.xml')
                            )
                        )
                    ),
                ],
                [
                    new JsonEncoder()
                ]
            );
        }
        
        return $serializer;
    }
}
