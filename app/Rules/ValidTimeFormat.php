<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidTimeFormat implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // Check if the value matches the H:i format (24-hour format)
        return preg_match('/^([01][0-9]|2[0-3]):([0-5][0-9])$/', $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute must be in the format H:i (e.g., 09:30).';
    }
}
