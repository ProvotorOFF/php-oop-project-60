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
}
