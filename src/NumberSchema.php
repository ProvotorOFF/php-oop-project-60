<?php

namespace Hexlet\Validator;

class NumberSchema extends Schema
{
    public function positive(): self
    {
        $this->rules['positive'] = fn($value) => $value === null || $value > 0;
        return $this;
    }

    public function range(int $min, int $max): self
    {
        $this->rules['range'] = fn($value) => $value >= $min && $value <= $max;
        return $this;
    }

    protected function typeHint(mixed $value): bool
    {
        return is_numeric($value) || !$value;
    }

    protected function setRequired(): void
    {
        $this->rules['required'] = fn($value) => $value !== null;
    }
}
