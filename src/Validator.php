<?php

namespace Hexlet\Code;

class Validator
{
    private array $validators = [];
    public function string(): StringSchema
    {
        return new StringSchema($this->validators['string'] ?? []);
    }

    public function number(): NumberSchema
    {
        return new NumberSchema($this->validators['number'] ?? []);
    }

    public function array(): ArraySchema
    {
        return new ArraySchema($this->validators['array'] ?? []);
    }

    public function addValidator(string $type, string $name, callable $fn): void
    {
        $this->validators[$type][$name] = $fn;
    }
}
