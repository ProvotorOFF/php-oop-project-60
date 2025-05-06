<?php

namespace Hexlet\Validator;

class ArraySchema extends Schema
{
    protected function typeHint(mixed $value): bool 
    {
        return is_array($value) || !$value;
    }

    protected function setRequired(): void
    {
        $this->rules['required'] = fn($value) => $value !== null;
    }

    public function sizeof(int $size): self
    {
        $this->rules['sizeof'] = fn($value) => sizeof($value) === $size; 
        return $this;
    }

    public function shape(array $shape): self
    {
        $this->rules['shape'] = function($value) use($shape) {
            foreach ($shape as $key => $schema) {

                if (!array_key_exists($key, $value)) {
                    if ($schema->isRequired()) {
                        return false;
                    }
                    continue;
                }

                if (!$schema->isValid($value[$key])) {
                    return false;
                }
            }
    
            return true;
        };
        return $this;
    }
}