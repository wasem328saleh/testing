<?php

namespace App\Rules;

use App\Traits\GeneralTrait;
use Closure;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Contracts\Validation\ValidationRule;

class RelationshipExists implements Rule
{
    use GeneralTrait;

    protected $firstId;
    protected $secondId;
    protected $relationshipName;
    protected $model;

    /**
     * RelationshipExists constructor.
     * @param $firstId
     * @param $secondId
     * @param $relationshipName
     * @param $model
     */
    public function __construct($firstId, $secondId, $relationshipName, $model)
    {
        $this->firstId = $firstId;
        $this->secondId = $secondId;
        $this->relationshipName = $relationshipName;
        $this->model = $model;
    }


    public function passes($attribute, $value)
    {
        $firstId = $this->firstId;
        $secondId = $this->secondId;
        $exists = $this->model::whereHas($this->relationshipName, function ($query) use ($secondId) {
            $query->where('id', $secondId);
        })->where('id', $firstId)->exists();
        return $exists;
    }

    public function message()
    {
        $k = $this->after_last("\\", strval($this->model));
        return 'The relationship between ' . $k . ' and ' . $this->relationshipName . ' does not exist.';
    }
}
