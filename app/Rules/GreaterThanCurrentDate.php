<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class GreaterThanCurrentDate implements Rule
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
        // Convert the input value to a DateTime object
        $inputDate = \DateTime::createFromFormat('Y-m-d', $value);

        // Get the current date
        $currentDate = new \DateTime();

        // Compare the input date with the current date
        return $inputDate > $currentDate;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute must be a date greater than the current date.';
    }
}
