<?php

namespace Hexlet\Code\Tests;

use PHPUnit\Framework\TestCase;
use Hexlet\Code\Validator;

class ValidatorTest extends TestCase
{
    public function testStringSchema(): void
    {
        $v = new Validator();
        $schema = $v->string();

        $this->assertTrue($schema->isValid(null));
        $this->assertTrue($schema->isValid(''));
        $this->assertTrue($schema->isValid('hello'));

        $schema->required();
        $this->assertFalse($schema->isValid(null));
        $this->assertFalse($schema->isValid(''));
        $this->assertTrue($schema->isValid('hexlet'));

        $schema2 = $v->string()->contains('hex');
        $this->assertTrue($schema2->isValid('hexlet is cool'));
        $this->assertFalse($schema2->isValid('no match here'));

        $schema3 = $v->string()->minLength(5);
        $this->assertTrue($schema3->isValid('hello'));
        $this->assertFalse($schema3->isValid('hi'));

        $schema4 = $v->string()->required()->minLength(4)->contains('hex');
        $this->assertTrue($schema4->isValid('hexlet'));
        $this->assertFalse($schema4->isValid('hex'));
        $this->assertTrue($schema4->isValid('lethex'));
    }

    public function testNumberSchema(): void
    {
        $v = new Validator();
        $schema = $v->number();

        $this->assertTrue($schema->isValid(null));

        $schema->required();
        $this->assertFalse($schema->isValid(null));
        $this->assertTrue($schema->isValid(0));
        $this->assertTrue($schema->isValid(10));
        $this->assertFalse($schema->isValid('string'));

        $schema2 = $v->number()->positive();
        $this->assertTrue($schema2->isValid(5));
        $this->assertFalse($schema2->isValid(-5));
        $this->assertFalse($schema2->isValid(0));

        $schema3 = $v->number()->range(1, 10);
        $this->assertTrue($schema3->isValid(1));
        $this->assertTrue($schema3->isValid(10));
        $this->assertFalse($schema3->isValid(0));
        $this->assertFalse($schema3->isValid(11));

        $schema4 = $v->number()->required()->positive()->range(5, 15);
        $this->assertTrue($schema4->isValid(10));
        $this->assertFalse($schema4->isValid(0));
        $this->assertFalse($schema4->isValid(16));
        $this->assertFalse($schema4->isValid(null));
    }

    public function testArraySchema(): void
    {
        $v = new Validator();
        $schema = $v->array();
        $this->assertTrue($schema->isValid(null));

        $schema = $schema->required();

        $this->assertTrue($schema->isValid([]));
        $this->assertTrue($schema->isValid(['hexlet']));

        $schema->sizeof(2);

        $this->assertFalse($schema->isValid(['hexlet']));
        $this->assertTrue($schema->isValid(['hexlet', 'code-basics']));

        $schema->shape([
            'name' => $v->string()->required(),
            'age' => $v->number()->positive(),
        ]);

        $this->assertTrue($schema->isValid(['name' => 'kolya', 'age' => 100]));
        $this->assertTrue($schema->isValid(['name' => 'maya', 'age' => null]));
        $this->assertFalse($schema->isValid(['name' => '', 'age' => null]));
        $this->assertFalse($schema->isValid(['name' => 'ada', 'age' => -5]));
    }

    public function testCustomRules(): void
    {

        $v = new Validator();

        $fn = fn($value, $start) => str_starts_with($value, $start);
        $v->addValidator('string', 'startWith', $fn);

        $schema = $v->string()->test('startWith', 'H');
        $this->assertFalse($schema->isValid('exlet'));
        $this->assertTrue($schema->isValid('Hexlet'));

        $fn = fn($value, $min) => $value >= $min;
        $v->addValidator('number', 'min', $fn);

        $schema = $v->number()->test('min', 5);
        $this->assertFalse($schema->isValid(4));
        $this->assertTrue($schema->isValid(6));
    }
}
