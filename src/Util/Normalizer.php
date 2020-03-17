<?php
declare(strict_types=1);

namespace Jalismrs\Stalactite\Client\Util;

use Jalismrs\Stalactite\Client\Exception\SerializerException;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\XmlFileLoader;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use function array_replace_recursive;

/**
 * Normalizer
 *
 * @package Jalismrs\Stalactite\Service\Util
 */
class Normalizer
{
    private const CONFIG_FILE = __DIR__ . '/../../config/serialization.xml';

    private const CONTEXT = [
        AbstractNormalizer::GROUPS => ['common']
    ];

    private static ?self $instance = null;

    private Serializer $serializer;

    /**
     * Normalizer constructor.
     */
    private function __construct()
    {
        $this->serializer = new Serializer(
            [
                new ObjectNormalizer(
                    new ClassMetadataFactory(new XmlFileLoader(self::CONFIG_FILE))
                )
            ],
            [new JsonEncoder()]
        );
    }

    /**
     * @return static
     */
    public static function getInstance(): self
    {
        if (!(self::$instance instanceof self)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * @param $data
     * @param array $context
     * @return array
     * @throws SerializerException
     */
    public function normalize($data, array $context = []): array
    {
        try {
            return $this->serializer->normalize($data, 'json', array_replace_recursive(self::CONTEXT, [], $context));
        } catch (ExceptionInterface $exception) {
            throw new SerializerException('Error while normalizing data', $exception->getCode(), $exception);
        }
    }
}
