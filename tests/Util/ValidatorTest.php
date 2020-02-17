<?php

namespace Jalismrs\Stalactite\Client\Tests\Util;

use hunomina\DataValidator\Rule\Json\JsonRule;
use Jalismrs\Stalactite\Client\Exception\ValidatorException;
use Jalismrs\Stalactite\Client\Util\Validator;
use PHPUnit\Framework\TestCase;

class ValidatorTest extends TestCase
{
    /**
     * @throws ValidatorException
     */
    public function testThrowOnInvalidDataSetData(): void
    {
        $this->expectException(ValidatorException::class);
        $this->expectExceptionCode(ValidatorException::INVALID_DATA_SET);

        Validator::getInstance()->setData(0);
    }

    /**
     * @throws ValidatorException
     */
    public function testThrowOnInvalidSchemaSetSchema(): void
    {
        $this->expectException(ValidatorException::class);
        $this->expectExceptionCode(ValidatorException::INVALID_SCHEMA);

        Validator::getInstance()->setSchema(['invalid-schema']);
    }

    /**
     * @throws ValidatorException
     */
    public function testThrowOnInvalidDataValidation(): void
    {
        $this->expectException(ValidatorException::class);
        $this->expectExceptionCode(ValidatorException::INVALID_DATA);

        $validator = Validator::getInstance();
        $validator->setData([])
            ->setSchema([
                'field' => ['type' => JsonRule::STRING_TYPE]
            ]);

        $validator->validate();
    }
}