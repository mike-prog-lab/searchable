<?php

namespace MikeProgLab\Searchable\Rules;

use Illuminate\Contracts\Validation\Rule;
use MikeProgLab\Searchable\Exception\Filter\MakeSearchFilterFromArrayException;
use MikeProgLab\Searchable\Filter\FilterType;

class SearchFilterRule implements Rule
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
     * @param string $attribute
     * @param mixed $value
     * @return bool
     * @throws MakeSearchFilterFromArrayException
     */
    public function passes($attribute, $value): bool
    {
        if (! is_array($value)) {
            return false;
        }

        FilterType::searchFilterFromArray($value);

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'The :attribute must be of valid FilterType format. ' .
            'Check API documentation to see correct format and available filter types.';
    }
}
