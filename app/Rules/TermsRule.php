<?php

namespace App\Rules;

use App\Models\CompanyInformation;
use Illuminate\Contracts\Validation\Rule;

class TermsRule implements Rule
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
        return sizeof($value) ==2 ;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        $companyInformation = CompanyInformation::find(1);
        return "You must agree to ".$companyInformation->company_name." policies and procedures.";
    }
}
