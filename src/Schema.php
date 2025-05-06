<?php

namespace Hexlet\Validator;

abstract class Schema
{
    public array $rules = [];
    public function __construct(private array $customValidators = [])
    {
    }

    public function required(): static
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
            if (is_callable($rule)) {
                if (!$rule($value)) {
                    return false;
                }
            } elseif (is_array($rule) && is_callable($rule[0])) {
                [$fn, $args] = $rule;
                if (!$fn($value, ...$args)) {
                    return false;
                }
            }
        }

        return true;
    }

    public function test(string $name, ...$args): self
    {
        $fn = $this->customValidators[$name];
        $this->rules[] = [$fn, $args];
        return $this;
    }

    abstract protected function typeHint(mixed $value): bool;

    abstract protected function setRequired(): void;
}
