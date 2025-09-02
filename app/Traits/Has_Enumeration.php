<?php


namespace App\Traits;


use Illuminate\Support\Facades\DB;
use ReflectionClass;


trait Has_Enumeration
{
    public function getAll()
    {
        $Class = new ReflectionClass ( $this );
        $constants = $Class->getConstants();
        return $constants;
    }

    function get_name($variable) {
        $Class = new ReflectionClass ( $this );
        $constants = $Class->getConstants();

//        return $constants;
        $constName = null;
        foreach ( $constants as $name => $value )
        {
            if ( $value == $variable )
            {
                $constName = $name;
                break;
            }
        }

        return $constName;
    }
    function get_value($constName) {
        $Class = new ReflectionClass ( $this );
        $constants = $Class->getConstants();

//        return $constants;
        $constValue = null;
        foreach ( $constants as $name => $value )
        {
            if ( $name == $constName )
            {
                $constValue = $value;
                break;
            }
        }

        return $constValue;
    }
    function setup($model,$name_fild,$name)
    {
        return $model::where($name_fild,$name)->first()->pluck('id');
    }

    function getEnumValues($model, $field)
    {
        $table = (new $model)->getTable();
        $columnInfo = DB::selectOne("SHOW COLUMNS FROM $table WHERE Field = ?", [$field]);
        preg_match('/^enum\((.*)\)$/', $columnInfo->Type, $matches);
        $enumValues = explode(',', $matches[1]);
        $enumValues = array_map(function ($value) {
            return trim($value, "'");
        }, $enumValues);

        return $enumValues;
    }

    function getId($model, $field, $name)
    {
        $id=$model::where($field,$name)->first()->id;
        return $id;
    }



}
