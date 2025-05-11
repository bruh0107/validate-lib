<?php

use src\Validator\Validator;

function createValidator(array $fields, array $rules, array $messages = []): Validator
{
    return new Validator($fields, $rules, $messages);
}