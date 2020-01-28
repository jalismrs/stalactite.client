<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Util;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Exception\BadMethodCallException;
use Symfony\Component\Serializer\Exception\CircularReferenceException;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Exception\ExtraAttributesException;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Exception\LogicException;
use Symfony\Component\Serializer\Exception\MappingException;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\Exception\NotNormalizableValueException;
use Symfony\Component\Serializer\Exception\RuntimeException;
use Symfony\Component\Serializer\Exception\UnexpectedValueException;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\XmlFileLoader;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer as SerializerObject;
use function array_replace_recursive;

/**
 * Serializer
 *
 * @package Jalismrs\Stalactite\Client\Util
 */
final class Serializer
{
    private const CONFIG_FILE = __DIR__ . '/../../config/serialization.xml';

    private const CONTEXT = [
        AbstractNormalizer::GROUPS => [
            'common',
        ],
    ];

    /**
     * @var SerializerObject
     */
    private $serializer;

    private static $instance;

    /**
     * Serializer constructor.
     *
     * @throws InvalidArgumentException
     * @throws LogicException
     * @throws MappingException
     */
    private function __construct()
    {
        $this->serializer = new SerializerObject(
            [
                new ObjectNormalizer(
                    new ClassMetadataFactory(
                        new XmlFileLoader(self::CONFIG_FILE)
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
     * @throws InvalidArgumentException
     * @throws LogicException
     * @throws MappingException
     */
    public static function getInstance(): self
    {
        if (!(self::$instance instanceof self)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * normalize
     *
     * @param       $data
     * @param array $context
     *
     * @return array
     *
     * @throws CircularReferenceException
     * @throws ExceptionInterface
     * @throws InvalidArgumentException
     * @throws LogicException
     */
    public function normalize($data, array $context = []): array
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
     * @param array $context
     *
     * @return array|object
     *
     * @throws BadMethodCallException
     * @throws ExceptionInterface
     * @throws ExtraAttributesException
     * @throws InvalidArgumentException
     * @throws LogicException
     * @throws NotNormalizableValueException
     * @throws RuntimeException
     * @throws UnexpectedValueException
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
     * @throws NotEncodableValueException
     */
    public function serialize($data, array $context = []): string
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
     * @param array $context
     *
     * @return array|object
     *
     * @throws NotEncodableValueException
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
