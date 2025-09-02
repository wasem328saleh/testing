<?php


namespace App\Traits;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class Server_CRUD
{
    use GeneralTrait;
    public $model;
    public $key;

    public function __construct($model)
    {
        $this->model=$model;
        $this->key=$this->after("App\\Models\\",strval($this->$model));
    }

    public function CRUD_Create($data)
    {
        try {
            $model=$this->model;
            $result=$model::create($data);
            return $this->returnData($this->key,$result,"Craete Done");
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }


}
