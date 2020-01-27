<?php
declare(strict_types = 1);

namespace Jalismrs\Stalactite\Client\Util;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\XmlFileLoader;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer as SerializerObject;
use function array_replace_recursive;
use function Jalismrs\Stalactite\Client\path;

/**
 * Serializer
 *
 * @package Jalismrs\Stalactite\Client\Util
 */
final class Serializer
{
    private const CONTEXT = [
        'groups' => [
            'common',
        ],
    ];
    
    /**
     * @var \Symfony\Component\Serializer\Serializer
     */
    private $serializer;
    
    /**
     * Serializer constructor.
     *
     * @throws \Symfony\Component\Serializer\Exception\InvalidArgumentException
     * @throws \Symfony\Component\Serializer\Exception\LogicException
     * @throws \Symfony\Component\Serializer\Exception\MappingException
     */
    public function __construct()
    {
        $this->serializer = new SerializerObject(
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
    
    /**
     * create
     *
     * @static
     * @return static
     *
     * @throws \Symfony\Component\Serializer\Exception\InvalidArgumentException
     * @throws \Symfony\Component\Serializer\Exception\LogicException
     * @throws \Symfony\Component\Serializer\Exception\MappingException
     */
    public static function create() : self
    {
        static $serializer = null;
        
        if (null === $serializer) {
            $serializer = new self();
        }
        
        return $serializer;
    }
    
    /**
     * normalize
     *
     * @param       $data
     * @param array $context
     *
     * @return array
     *
     * @throws \Symfony\Component\Serializer\Exception\CircularReferenceException
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     * @throws \Symfony\Component\Serializer\Exception\InvalidArgumentException
     * @throws \Symfony\Component\Serializer\Exception\LogicException
     */
    public function normalize($data, array $context = []) : array
    {
        return $this->serializer->normalize(
            $data,
            'json',
            array_replace_recursive(
                self::CONTEXT,
                [],
                $context
            )
        );
    }
    
    /**
     * denormalize
     *
     * @param        $data
     * @param string $type
     * @param array  $context
     *
     * @return array|object
     *
     * @throws \Symfony\Component\Serializer\Exception\BadMethodCallException
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     * @throws \Symfony\Component\Serializer\Exception\ExtraAttributesException
     * @throws \Symfony\Component\Serializer\Exception\InvalidArgumentException
     * @throws \Symfony\Component\Serializer\Exception\LogicException
     * @throws \Symfony\Component\Serializer\Exception\NotNormalizableValueException
     * @throws \Symfony\Component\Serializer\Exception\RuntimeException
     * @throws \Symfony\Component\Serializer\Exception\UnexpectedValueException
     */
    public function denormalize($data, string $type, array $context = [])
    {
        return $this->serializer->denormalize(
            $data,
            $type,
            'json',
            array_replace_recursive(
                self::CONTEXT,
                [],
                $context
            )
        );
    }
    
    /**
     * serialize
     *
     * @param       $data
     * @param array $context
     *
     * @return string
     *
     * @throws \Symfony\Component\Serializer\Exception\NotEncodableValueException
     */
    public function serialize($data, array $context = []) : string
    {
        return $this->serializer->serialize(
            $data,
            'json',
            array_replace_recursive(
                self::CONTEXT,
                [],
                $context
            )
        );
    }
    
    /**
     * deserialize
     *
     * @param        $data
     * @param string $type
     * @param array  $context
     *
     * @return array|object
     *
     * @throws \Symfony\Component\Serializer\Exception\NotEncodableValueException
     */
    public function deserialize($data, string $type, array $context = [])
    {
        return $this->serializer->deserialize(
            $data,
            $type,
            'json',
            array_replace_recursive(
                self::CONTEXT,
                [],
                $context
            )
        );
    }
}
