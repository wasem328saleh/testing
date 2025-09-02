<?php

namespace App\Rules;

use App\Traits\GeneralTrait;
use Closure;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Contracts\Validation\ValidationRule;

class RentTypeRule implements Rule
{
    use GeneralTrait;

    protected $rent_type;
    protected $publication_type;

    /**
     * RelationshipExists constructor.
     * @param $rent_type
     * @param $publication_type
     */
    public function __construct($rent_type,$publication_type)
    {
        $this->rent_type = $rent_type;
        $this->publication_type = $publication_type;
    }


    public function passes($attribute, $value)
    {
        $rent_type = $this->rent_type;
        $publication_type = $this->publication_type;
        if ($publication_type === 'rent')
        {
            $exists=$rent_type==null;
        }

        return $exists;
    }

    public function message()
    {

        return 'Please specify the rental type if you wish to post a property for rent.';
    }
}
