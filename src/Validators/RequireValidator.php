<?php

namespace Validators;

class RequireValidator extends AbstractValidator
{

    protected string $message = 'Field :field is required';

    public function rule(): bool
    {
        return !empty($this->value) && strlen(trim($this->value));
    }
}