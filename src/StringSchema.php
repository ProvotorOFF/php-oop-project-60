<?php

namespace Hexlet\Validator;

class StringSchema extends Schema
{
    public function minLength(int $length): self
    {
        $this->rules['minLength'] = fn($value) => mb_strlen($value) >= $length;
        return $this;
    }

    public function contains(string $substring): self
    {
        $this->rules['contains'] = fn($value) => str_contains($value, $substring);
        return $this;
    }

    protected function typeHint(mixed $value): bool
    {
        return is_string($value) || !(bool)$value;
    }

    protected function setRequired(): void
    {
        $this->rules['required'] = fn($value) => $value !== null && (bool)mb_strlen($value);
    }
}
