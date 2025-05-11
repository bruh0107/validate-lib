<?php

namespace Validators;

class MaxLengthValidator extends AbstractValidator
{
    protected string $message = 'Field :field must not exceed :max characters';

    public function rule(): bool
    {
        $length = mb_strlen($this->value);
        return $length <= $this->args[0];
    }
}