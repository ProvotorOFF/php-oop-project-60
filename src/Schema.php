<?php

namespace Hexlet\Code;

abstract class Schema
{
    public array $rules = [];

    public function required(): self
    {
        if (isset($this->rules['required'])) {
            unset($this->rules['required']);
        } else {
            $this->setRequired();
        }
        return $this;
    }

    public function isValid($value): bool
    {

        if (!$this->typeHint($value)) {
            return false;
        }

        foreach ($this->rules as $rule) {
            if (!$rule($value)) {
                return false;
            }
        }

        return true;
    }

    abstract protected function typeHint(mixed $value): bool;

    abstract protected function setRequired(): void;
}
