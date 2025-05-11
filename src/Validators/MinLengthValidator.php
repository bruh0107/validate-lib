<?php

namespace Validators;

class MinLengthValidator extends AbstractValidator
{
    protected string $message = 'Field :field must be at least :min characters long';

    public function rule(): bool
    {
        $length = mb_strlen($this->value);
        return $length >= $this->args[0];
    }
}