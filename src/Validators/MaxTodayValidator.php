<?php

namespace Validator\Validators;

use DateTime;

class MaxTodayValidator extends AbstractValidator
{
    public function rule(): bool
    {
        if (empty($this->value)) {
            return true;
        }

        try {
            $inputDate = new DateTime($this->value);
            $today = new DateTime('today');

            return $inputDate <= $today;
        } catch (\Exception $e) {
            return false;
        }
    }
}