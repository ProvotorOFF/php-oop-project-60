<?php

namespace Hexlet\Code;

class Validator
{
    public function string(): StringSchema
    {
        return new StringSchema();
    }

    public function number(): NumberSchema
    {
        return new NumberSchema();
    }

    public function array(): ArraySchema
    {
        return new ArraySchema();
    }
}