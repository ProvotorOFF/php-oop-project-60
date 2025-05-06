<?php

namespace Hexlet\Validator\Tests;

use PHPUnit\Framework\TestCase;
use Hexlet\Validator\Validator;

class ValidatorTest extends TestCase
{
    public function testStringSchema(): void
    {
        $v = new Validator();
        $schema = $v->string();

        self::assertTrue($schema->isValid(null));
        self::assertTrue($schema->isValid(''));
        self::assertTrue($schema->isValid('hello'));

        $schema->required();
        self::assertFalse($schema->isValid(null));
        self::assertFalse($schema->isValid(''));
        self::assertTrue($schema->isValid('hexlet'));

        $schema2 = $v->string()->contains('hex');
        self::assertTrue($schema2->isValid('hexlet is cool'));
        self::assertFalse($schema2->isValid('no match here'));

        $schema3 = $v->string()->minLength(5);
        self::assertTrue($schema3->isValid('hello'));
        self::assertFalse($schema3->isValid('hi'));

        $schema4 = $v->string()->required()->minLength(4)->contains('hex');
        self::assertTrue($schema4->isValid('hexlet'));
        self::assertFalse($schema4->isValid('hex'));
        self::assertTrue($schema4->isValid('lethex'));
    }

    public function testNumberSchema(): void
    {
        $v = new Validator();
        $schema = $v->number();

        self::assertTrue($schema->isValid(null));

        $schema->required();
        self::assertFalse($schema->isValid(null));
        self::assertTrue($schema->isValid(0));
        self::assertTrue($schema->isValid(10));
        self::assertFalse($schema->isValid('string'));

        $schema2 = $v->number()->positive();
        self::assertTrue($schema2->isValid(5));
        self::assertFalse($schema2->isValid(-5));
        self::assertFalse($schema2->isValid(0));

        $schema3 = $v->number()->range(1, 10);
        self::assertTrue($schema3->isValid(1));
        self::assertTrue($schema3->isValid(10));
        self::assertFalse($schema3->isValid(0));
        self::assertFalse($schema3->isValid(11));

        $schema4 = $v->number()->required()->positive()->range(5, 15);
        self::assertTrue($schema4->isValid(10));
        self::assertFalse($schema4->isValid(0));
        self::assertFalse($schema4->isValid(16));
        self::assertFalse($schema4->isValid(null));
    }

    public function testArraySchema(): void
    {
        $v = new Validator();
        $schema = $v->array();
        self::assertTrue($schema->isValid(null));

        $schema = $schema->required();

        self::assertTrue($schema->isValid([]));
        self::assertTrue($schema->isValid(['hexlet']));

        $schema->sizeof(2);

        self::assertFalse($schema->isValid(['hexlet']));
        self::assertTrue($schema->isValid(['hexlet', 'code-basics']));

        $schema->shape([
            'name' => $v->string()->required(),
            'age' => $v->number()->positive(),
        ]);

        self::assertTrue($schema->isValid(['name' => 'kolya', 'age' => 100]));
        self::assertTrue($schema->isValid(['name' => 'maya', 'age' => null]));
        self::assertFalse($schema->isValid(['name' => '', 'age' => null]));
        self::assertFalse($schema->isValid(['name' => 'ada', 'age' => -5]));
    }

    public function testCustomRules(): void
    {

        $v = new Validator();

        $fn = fn($value, $start) => str_starts_with($value, $start);
        $v->addValidator('string', 'startWith', $fn);

        $schema = $v->string()->test('startWith', 'H');
        self::assertFalse($schema->isValid('exlet'));
        self::assertTrue($schema->isValid('Hexlet'));

        $fn = fn($value, $min) => $value >= $min;
        $v->addValidator('number', 'min', $fn);

        $schema = $v->number()->test('min', 5);
        self::assertFalse($schema->isValid(4));
        self::assertTrue($schema->isValid(6));
    }
}
