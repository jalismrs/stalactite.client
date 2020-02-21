<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Util;

use Jalismrs\Stalactite\Client\Exception\SerializerException;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Exception\LogicException;
use Symfony\Component\Serializer\Exception\MappingException;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\XmlFileLoader;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer as SerializerObject;
use function array_replace_recursive;

/**
 * Serializer
 *
 * @package Jalismrs\Stalactite\Service\Util
 */
class Serializer
{
    private const CONFIG_FILE = __DIR__ . '/../../config/serialization.xml';

    private const CONTEXT = [
        AbstractNormalizer::GROUPS => [
            'common',
        ],
    ];
    
    /**
     * @static
     * @var Serializer|null
     */
    private static ?self $instance = null;
    /**
     * @var SerializerObject
     */
    private SerializerObject $serializer;

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
     * @return static
     * @throws SerializerException
     */
    public static function getInstance(): self
    {
        try {
            if (!(self::$instance instanceof self)) {
                self::$instance = new self();
            }
        } catch (ExceptionInterface $exception) {
            throw new SerializerException(
                'Error while instantiating ' . self::class,
                $exception->getCode(),
                $exception
            );
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
     * @throws SerializerException
     */
    public function normalize($data, array $context = []): array
    {
        try {
            return $this->serializer->normalize(
                $data,
                'json',
                array_replace_recursive(
                    self::CONTEXT,
                    [],
                    $context
                )
            );
        } catch (ExceptionInterface $exception) {
            throw new SerializerException(
                'Error while normalizing data',
                $exception->getCode(),
                $exception
            );
        }
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
     * @throws SerializerException
     */
    public function denormalize($data, string $type, array $context = [])
    {
        //TODO: not tested
        try {
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
        } catch (ExceptionInterface $exception) {
            throw new SerializerException(
                'Error while denormalizing data',
                $exception->getCode(),
                $exception
            );
        }
    }

    /**
     * serialize
     *
     * @param       $data
     * @param array $context
     *
     * @return string
     *
     * @throws SerializerException
     */
    public function serialize($data, array $context = []): string
    {
        //TODO: not tested
        try {
            return $this->serializer->serialize(
                $data,
                'json',
                array_replace_recursive(
                    self::CONTEXT,
                    [],
                    $context
                )
            );
        } catch (ExceptionInterface $exception) {
            throw new SerializerException(
                'Error while serializing data',
                $exception->getCode(),
                $exception
            );
        }
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
     * @throws SerializerException
     */
    public function deserialize($data, string $type, array $context = [])
    {
        //TODO: not tested
        try {
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
        } catch (ExceptionInterface $exception) {
            throw new SerializerException(
                'Error while deserializing data',
                $exception->getCode(),
                $exception
            );
        }
    }
}
