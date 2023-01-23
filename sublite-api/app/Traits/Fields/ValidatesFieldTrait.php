<?php

namespace App\Traits\Fields;

use App\Models\Field;

trait ValidatesFieldTrait
{
    /**
     * 
     * Validate the field
     * 
     * @return string|null error message, or null in case of no error
     */
    public function validate(mixed $value): ?string
    {
        if (!$this->required && !$value) {
            return null;
        }

        if ($this->required && !$value) {
            return __('Field is required.');
        }

        switch ($this->type) {
            case Field::TYPE_DATE:
                if (!(bool)strtotime($value)) {
                    return __('Value must be a date.');
                }
                break;
            case Field::TYPE_NUMBER:
                if (!is_numeric($value)) {
                    return __('Value must be a number.');
                }
                break;
            case Field::TYPE_BOOLEAN:
                if (!in_array($value, [true, false, 0, 1], false)) {
                    return __('Value must be a boolean.');
                }
                break;
            case Field::TYPE_STRING:
                if (strlen($value) > Field::STRING_MAX_LENGTH) {
                    return __('Length cannot be greater than :maxlength.', ['maxlength' => Field::STRING_MAX_LENGTH]);
                }
                break;
        }

        return null;
    }
}