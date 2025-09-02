<?php


namespace App\Traits;


use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use function League\Uri\UriTemplate\toString;

trait CRUD
{
    use GeneralTrait;

    public function CRUD_Create($model, $request)
    {
        try {
            $result = $model::create($request->all());
            $k = $this->after_last("\\", strval($model));
            return $this->returnData($k, $result, trans('messages.success'));

        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function CRUD_Edit($model, $request)
    {
        try {
            $ob = $model::find($request->id);
            if ($ob != null) {
                $k = $this->after_last("\\", strval($model));
                return $this->returnData($k, $ob, "This is " . $k);
            }
            return $this->returnError(55, "Not Found ");

        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function CRUD_Update($model, $request)
    {
        try {
            $ob = $model::find($request->id);

            if ($ob != null) {
                $ob->update($request->all());
                $k = $this->after("App\\Models\\", strval($model));

                return $this->returnData($k, $ob, trans('messages.Update'));

            }
            return $this->returnError(55, trans('messages.not_found'));


        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function CRUD_Delete($model, $request)
    {
        try {

            if (isset($request['id'])) {
                $id = $request['id'];
                $ob = $model::find($id);
                if ($ob) {
                    $model::destroy($request['id']);

                    $k = $this->after_last("\\", strval($model));

                    return $this->returnSuccessMessage(trans('messages.Delete'));
                }


                return $this->returnError(55, trans('messages.not_found'));
            }
        } catch
        (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function CRUD_Show($model, $request)
    {
        try {

            if (isset($request['id'])) {
                $id = $request['id'];
                $ob = $model::find($id);


                if ($ob != null) {
                    $k = $this->after_last("\\", strval($model));
                    return $this->returnData($k, $ob, '');
                }
                return $this->returnError(55, trans('messages.not_found'));
            }

        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function CRUD_Get_All($model)
    {
        try {
            $All = $model::all();
            $k = $this->after_last("\\", strval($model));
            return $this->returnData($k, $All, '');
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
    public function t($Path_Models, $model_name = null, $needed, $nameInterface = '')
    {
        $b = $needed;
//        $Path_Models='C:\Users\wasem\Desktop\مشروع التخرج\Code\aqar_backend\app\Models';
        $php_files = '';
        if ($model_name) {
            $php_files = glob($Path_Models . '/' . $model_name . '.php');
        } else {
            $php_files = glob($Path_Models . '/*.php');
        }
        foreach ($php_files as $file) {
            $model = nl2br(pathinfo($file)['filename']);
            if (!$this->hasContentUppercaseWithoutFirstChar($model)) {
                echo nl2br("\n\n//*********************[ " . $model . " ]*********************\n\n");

                if ($b == 1) {
                    $s = nl2br("public function Store_" . $model . "(Store" . $model . "Request \$request)\r" . "{
        return \$this->CRUD_Create(" . $model . "::class,\$request);\r}");
                    $e = nl2br("public function Edit_" . $model . "(Edit" . $model . "Request \$request)
        {
        return \$this->CRUD_Edit(" . $model . "::class,\$request);
        }");

                    $u = nl2br("public function Update_" . $model . "(Update" . $model . "Request \$request)
        {
        return \$this->CRUD_Update(" . $model . "::class,\$request);
        }");

                    $d = nl2br("public function Delete_" . $model . "(Delete" . $model . "Request \$request)
        {
        return \$this->CRUD_Delete(" . $model . "::class,\$request);
        }");

                    $so = nl2br("public function Get_" . $model . "(Get" . $model . "Request \$request)
        {
        return \$this->CRUD_Show(" . $model . "::class,\$request);
        }");

                    $ga = nl2br("public function Get_All_" . $model . "s()
        {
        return \$this->CRUD_Get_All(" . $model . "::class);
        }");
                    $functions = nl2br($s . "\n"
                        . $e . "\n"
                        . $u . "\n"
                        . $d . "\n"
                        . $so . "\n"
                        . $ga . "\n");

                    $CRUD = nl2br($functions);
                    echo nl2br($CRUD);
                } elseif ($b == 2) {

                    $si = nl2br("public function Store_" . $model . "(Store" . $model . "Request \$request);");
                    $ei = nl2br("public function Edit_" . $model . "(Edit" . $model . "Request \$request);");
                    $ui = nl2br("public function Update_" . $model . "(Update" . $model . "Request \$request);");
                    $di = nl2br("public function Delete_" . $model . "(Delete" . $model . "Request \$request);");
                    $soi = nl2br("public function Get_" . $model . "(Get" . $model . "Request \$request);");
                    $gai = nl2br("public function Get_All_" . $model . "s();");


                    $Interfaces = nl2br($si . "\n"
                        . $ei . "\n"
                        . $ui . "\n"
                        . $di . "\n"
                        . $soi . "\n"
                        . $gai . "\n");
                    $CRUD = nl2br($Interfaces);
                    echo nl2br($CRUD);
                } elseif ($b == 3) {
                    $Controller_name = $model . "Controller";
                    $sr = nl2br("Route::post('store_" . strtolower($model) . "',[" . $Controller_name . "::class,'Store_" . $model . "']);");
                    $er = nl2br("Route::post('edit_" . strtolower($model) . "',[" . $Controller_name . "::class,'Edit_" . $model . "']);");
                    $ur = nl2br("Route::post('update_" . strtolower($model) . "',[" . $Controller_name . "::class,'Update_" . $model . "']);");
                    $dr = nl2br("Route::post('delete_" . strtolower($model) . "',[" . $Controller_name . "::class,'Delete_" . $model . "']);");
                    $sor = nl2br("Route::post('get_" . strtolower($model) . "',[" . $Controller_name . "::class,'Get_" . $model . "']);");
                    $gar = nl2br("Route::get('get_all_" . strtolower($model) . "',[" . $Controller_name . "::class,'Get_All_" . $model . "']);");


                    $Routes = nl2br($sr . "\n"
                        . $er . "\n"
                        . $ur . "\n"
                        . $dr . "\n"
                        . $sor . "\n"
                        . $gar . "\n");
                    $CRUD = nl2br($Routes);
                    echo nl2br($CRUD);
                } elseif ($b == 4 && $nameInterface != '') {
                    echo nl2br("\n\n//---------------------[ Interfaces ]---------------------\n\n");
                    $si = nl2br("public function Store_" . $model . "(\$request);");
                    $ei = nl2br("public function Edit_" . $model . "(\$request);");
                    $ui = nl2br("public function Update_" . $model . "(\$request);");
                    $di = nl2br("public function Delete_" . $model . "(\$request);");
                    $soi = nl2br("public function Show_" . $model . "(\$request);");
                    $gai = nl2br("public function Get_All_" . $model . "s();");


                    $Interfaces = nl2br($si . "\n" . $ei . "\n" . $ui . "\n" . $di . "\n"
                        . $soi . "\n"
                        . $gai . "\n");
                    echo $Interfaces;
                    echo nl2br("\n\n//---------------------[ Functions Repository]---------------------\n\n");

                    $srepo = "public function Store_" . $model . "(\$request)
                    {
                    return \$this->CRUD_Create(" . $model . "::class,\$request);
                    }";

                    $erepo = "public function Edit_" . $model . "(\$request)
                    {
                    return \$this->CRUD_Edit(" . $model . "::class,\$request);
                    }";

                    $urepo = "public function Update_" . $model . "(\$request)
                    {
                    return \$this->CRUD_Update(" . $model . "::class,\$request);
                    }";

                    $drepo = "public function Delete_" . $model . "(\$request)
                    {
                    return \$this->CRUD_Delete(" . $model . "::class,\$request);
                    }";

                    $sorepo = "public function Show_" . $model . "(\$request)
                    {
                    return \$this->CRUD_Show(" . $model . "::class,\$request);
                    }";

                    $garepo = "public function Get_All_" . $model . "s()
                    {
                    return \$this->CRUD_Get_All(" . $model . "::class);
                    }";
                    $functions = nl2br($srepo . "\n\n" . $erepo . "\n\n" . $urepo . "\n\n" . $drepo . "\n\n" . $sorepo . "\n\n" . $garepo);
                    echo $functions;
                    echo nl2br("\n\n//---------------------[ Functions Controller ]---------------------\n\n");

                    $s = "public function Store_" . $model . "(Store" . $model . "Request \$request)
                    {
                    return \$this->" . $nameInterface . "->Store_" . $model . "(" . $model . "::class,\$request);
                    }";

                    $e = "public function Edit_" . $model . "(Edit" . $model . "Request \$request)
                    {
                    return \$this->" . $nameInterface . "->Edit_" . $model . "(" . $model . "::class,\$request);
                    }";

                    $u = "public function Update_" . $model . "(Update" . $model . "Request \$request)
                    {
                    return \$this->" . $nameInterface . "->Update_" . $model . "(" . $model . "::class,\$request);
                    }";

                    $d = "public function Delete_" . $model . "(Delete" . $model . "Request \$request)
                    {
                    return \$this->" . $nameInterface . "->Delete_" . $model . "(" . $model . "::class,\$request);
                    }";

                    $so = "public function Show_" . $model . "(Show" . $model . "Request \$request)
                    {
                    return \$this->" . $nameInterface . "->Show_" . $model . "(" . $model . "::class,\$request);
                    }";

                    $ga = "public function Get_All_" . $model . "s()
                    {
                    return \$this->" . $nameInterface . "->Get_All_" . $model . "(" . $model . "::class);
                    }";

                    $functionsController = nl2br($s . "\n\n" . $e . "\n\n" . $u . "\n\n" . $d . "\n\n" . $so . "\n\n" . $ga);

                    echo $functionsController;
                    echo nl2br("\n\n//---------------------[ Routes ]---------------------\n\n");

                    $Controller_name = $nameInterface . "Controller";
                    $pstart = nl2br("Route::prefix('" . strtolower($model) . "')->group(function () {");
                    $sr = nl2br("Route::post('store',[" . $Controller_name . "::class,'Store_" . $model . "']);");
                    $er = nl2br("Route::post('edit',[" . $Controller_name . "::class,'Edit_" . $model . "']);");
                    $ur = nl2br("Route::post('update',[" . $Controller_name . "::class,'Update_" . $model . "']);");
                    $dr = nl2br("Route::post('delete',[" . $Controller_name . "::class,'Delete_" . $model . "']);");
                    $sor = nl2br("Route::post('show',[" . $Controller_name . "::class,'Show_" . $model . "']);");
                    $gar = nl2br("Route::get('get_all',[" . $Controller_name . "::class,'Get_All_" . $model . "']);");
                    $pend = nl2br("});");

                    $Routes = nl2br($pstart . "\n" . $sr . "\n"
                        . $er . "\n"
                        . $ur . "\n"
                        . $dr . "\n"
                        . $sor . "\n"
                        . $gar . "\n"
                        . $pend . "\n");
                    echo $Routes;


                } elseif ($b == 5) {
                    echo nl2br("\n\n//---------------------[ Interfaces ]---------------------\n\n");
                    $si = nl2br("public function Store_" . $model . "(Store" . $model . "Request \$request);");
                    $ei = nl2br("public function Edit_" . $model . "(Edit" . $model . "Request \$request);");
                    $ui = nl2br("public function Update_" . $model . "(Update" . $model . "Request \$request);");
                    $di = nl2br("public function Delete_" . $model . "(Delete" . $model . "Request \$request);");
                    $soi = nl2br("public function Get_" . $model . "(Get" . $model . "Request \$request);");
                    $gai = nl2br("public function Get_All_" . $model . "s();");


                    $Interfaces = nl2br($si . "\n"
                        . $ei . "\n"
                        . $ui . "\n"
                        . $di . "\n"
                        . $soi . "\n"
                        . $gai . "\n");
                    $CRUD1 = nl2br($Interfaces);
                    echo nl2br($CRUD1);
                    echo nl2br("\n\n//---------------------[ Functions ]---------------------\n\n");

                    $s = nl2br("public function Store_" . $model . "(Store" . $model . "Request \$request)\r" . "{
        return \$this->CRUD_Create(" . $model . "::class,\$request);\r}");
                    $e = nl2br("public function Edit_" . $model . "(Edit" . $model . "Request \$request)
        {
        return \$this->CRUD_Edit(" . $model . "::class,\$request);
        }");

                    $u = nl2br("public function Update_" . $model . "(Update" . $model . "Request \$request)
        {
        return \$this->CRUD_Update(" . $model . "::class,\$request);
        }");

                    $d = nl2br("public function Delete_" . $model . "(Delete" . $model . "Request \$request)
        {
        return \$this->CRUD_Delete(" . $model . "::class,\$request);
        }");

                    $so = nl2br("public function Get_" . $model . "(Get" . $model . "Request \$request)
        {
        return \$this->CRUD_Show(" . $model . "::class,\$request);
        }");

                    $ga = nl2br("public function Get_All_" . $model . "s()
        {
        return \$this->CRUD_Get_All(" . $model . "::class);
        }");
                    $functions = nl2br($s . "\n"
                        . $e . "\n"
                        . $u . "\n"
                        . $d . "\n"
                        . $so . "\n"
                        . $ga . "\n");

                    $CRUD2 = nl2br($functions);
                    echo nl2br($CRUD2);
                    echo nl2br("\n\n//---------------------[ Routes ]---------------------\n\n");

                    $Controller_name = $model . "Controller";
                    $sr = nl2br("Route::post('store_" . strtolower($model) . "',[" . $Controller_name . "::class,'Store_" . $model . "']);");
                    $er = nl2br("Route::post('edit_" . strtolower($model) . "',[" . $Controller_name . "::class,'Edit_" . $model . "']);");
                    $ur = nl2br("Route::post('update_" . strtolower($model) . "',[" . $Controller_name . "::class,'Update_" . $model . "']);");
                    $dr = nl2br("Route::post('delete_" . strtolower($model) . "',[" . $Controller_name . "::class,'Delete_" . $model . "']);");
                    $sor = nl2br("Route::post('get_" . strtolower($model) . "',[" . $Controller_name . "::class,'Get_" . $model . "']);");
                    $gar = nl2br("Route::get('get_all_" . strtolower($model) . "',[" . $Controller_name . "::class,'Get_All_" . $model . "']);");


                    $Routes = nl2br($sr . "\n"
                        . $er . "\n"
                        . $ur . "\n"
                        . $dr . "\n"
                        . $sor . "\n"
                        . $gar . "\n");
                    $CRUD3 = nl2br($Routes);
                    echo nl2br($CRUD3);
                }

            }


        }
    }

    public function hasContentUppercaseWithoutFirstChar($str)
    {
        $length = strlen($str);

        // Start iterating from the second character
        for ($i = 1; $i < $length; $i++) {
            if (ctype_upper($str[$i])) {
                return true; // Uppercase character found, return true
            }
        }

        return false; // No uppercase character found excluding the first character
    }

    public function generated($models, $nameInterface)
    {
        if (is_array($models)) {
            $Interfaces = array();
            $functions = array();
            $functionsController = array();
            $Routes = array();
            foreach ($models as $model) {
                $si = nl2br("public function Store_" . $model . "(\$request);");
                $ei = nl2br("public function Edit_" . $model . "(\$request);");
                $ui = nl2br("public function Update_" . $model . "(\$request);");
                $di = nl2br("public function Delete_" . $model . "(\$request);");
                $soi = nl2br("public function Show_" . $model . "(\$request);");
                $gai = nl2br("public function Get_All_" . $model . "s();");


                $Interface = nl2br($si . "\n" . $ei . "\n" . $ui . "\n" . $di . "\n"
                    . $soi . "\n"
                    . $gai . "\n\n");
                $Interfaces[] = $Interface;

                $srepo = "public function Store_" . $model . "(\$request)
                    {
                    return \$this->CRUD_Create(" . $model . "::class,\$request);
                    }";

                $erepo = "public function Edit_" . $model . "(\$request)
                    {
                    return \$this->CRUD_Edit(" . $model . "::class,\$request);
                    }";

                $urepo = "public function Update_" . $model . "(\$request)
                    {
                    return \$this->CRUD_Update(" . $model . "::class,\$request);
                    }";

                $drepo = "public function Delete_" . $model . "(\$request)
                    {
                    return \$this->CRUD_Delete(" . $model . "::class,\$request);
                    }";

                $sorepo = "public function Show_" . $model . "(\$request)
                    {
                    return \$this->CRUD_Show(" . $model . "::class,\$request);
                    }";

                $garepo = "public function Get_All_" . $model . "s()
                    {
                    return \$this->CRUD_Get_All(" . $model . "::class);
                    }";
                $function = nl2br($srepo . "\n\n" . $erepo . "\n\n" . $urepo . "\n\n" . $drepo . "\n\n" . $sorepo . "\n\n" . $garepo . "\n\n");
                $functions[] = $function;


                $s = "public function Store_" . $model . "(" . $model . "Request \$request)
                    {
                    return \$this->" . $nameInterface . "->Store_" . $model . "(\$request);
                    }";

                $e = "public function Edit_" . $model . "(" . $model . "Request \$request)
                    {
                    return \$this->" . $nameInterface . "->Edit_" . $model . "(\$request);
                    }";

                $u = "public function Update_" . $model . "(" . $model . "Request \$request)
                    {
                    return \$this->" . $nameInterface . "->Update_" . $model . "(\$request);
                    }";

                $d = "public function Delete_" . $model . "(" . $model . "Request \$request)
                    {
                    return \$this->" . $nameInterface . "->Delete_" . $model . "(\$request);
                    }";

                $so = "public function Show_" . $model . "(" . $model . "Request \$request)
                    {
                    return \$this->" . $nameInterface . "->Show_" . $model . "(\$request);
                    }";

                $ga = "public function Get_All_" . $model . "s()
                    {
                    return \$this->" . $nameInterface . "->Get_All_" . $model . "();
                    }";

                $functionController = nl2br($s . "\n\n" . $e . "\n\n" . $u . "\n\n" . $d . "\n\n" . $so . "\n\n" . $ga . "\n\n");

                $functionsController[] = $functionController;

                $Controller_name = $nameInterface . "Controller";
                $pstart = nl2br("Route::prefix('" . strtolower($model) . "')->group(function () {");
                $sr = nl2br("Route::post('create',[" . $Controller_name . "::class,'Store_" . $model . "']);");
                $er = nl2br("Route::post('edit',[" . $Controller_name . "::class,'Edit_" . $model . "']);");
                $ur = nl2br("Route::post('update',[" . $Controller_name . "::class,'Update_" . $model . "']);");
                $dr = nl2br("Route::post('delete',[" . $Controller_name . "::class,'Delete_" . $model . "']);");
                $sor = nl2br("Route::post('show',[" . $Controller_name . "::class,'Show_" . $model . "']);");
                $gar = nl2br("Route::get('get_all',[" . $Controller_name . "::class,'Get_All_" . $model . "']);");
                $pend = nl2br("});");

                $Route = nl2br($pstart . "\n" . $sr . "\n"
                    . $er . "\n"
                    . $ur . "\n"
                    . $dr . "\n"
                    . $sor . "\n"
                    . $gar . "\n"
                    . $pend . "\n\n");
                $Routes[] = $Route;
            }

            echo nl2br("\n\n//---------------------[ Interfaces ]---------------------\n\n");
            foreach ($Interfaces as $interface) {
                echo $interface;
            }

            echo nl2br("\n\n//---------------------[ Functions Repository]---------------------\n\n");
            foreach ($functions as $functionn) {
                echo $functionn;
            }

            echo nl2br("\n\n//---------------------[ Functions Controller ]---------------------\n\n");
            foreach ($functionsController as $item) {
                echo "\n" . $item;
            }

            echo nl2br("\n\n//---------------------[ Routes ]---------------------\n\n");
            foreach ($Routes as $route) {
                echo $route;
            }
            return;
        }

        echo nl2br("\n\n//*********************[ " . $models . " ]*********************\n\n");
        echo nl2br("\n\n//---------------------[ Interfaces ]---------------------\n\n");
        $si = nl2br("public function Store_" . $models . "(\$request);");
        $ei = nl2br("public function Edit_" . $models . "(\$request);");
        $ui = nl2br("public function Update_" . $models . "(\$request);");
        $di = nl2br("public function Delete_" . $models . "(\$request);");
        $soi = nl2br("public function Show_" . $models . "(\$request);");
        $gai = nl2br("public function Get_All_" . $models . "s();");


        $Interfaces = nl2br($si . "\n" . $ei . "\n" . $ui . "\n" . $di . "\n"
            . $soi . "\n"
            . $gai . "\n");
        echo $Interfaces;
        echo nl2br("\n\n//---------------------[ Functions Repository]---------------------\n\n");

        $srepo = "public function Store_" . $models . "(\$request)
                    {
                    return \$this->CRUD_Create(" . $models . "::class,\$request);
                    }";

        $erepo = "public function Edit_" . $models . "(\$request)
                    {
                    return \$this->CRUD_Edit(" . $models . "::class,\$request);
                    }";

        $urepo = "public function Update_" . $models . "(\$request)
                    {
                    return \$this->CRUD_Update(" . $models . "::class,\$request);
                    }";

        $drepo = "public function Delete_" . $models . "(\$request)
                    {
                    return \$this->CRUD_Delete(" . $models . "::class,\$request);
                    }";

        $sorepo = "public function Show_" . $models . "(\$request)
                    {
                    return \$this->CRUD_Show(" . $models . "::class,\$request);
                    }";

        $garepo = "public function Get_All_" . $models . "s()
                    {
                    return \$this->CRUD_Get_All(" . $models . "::class);
                    }";
        $functions = nl2br($srepo . "\n\n" . $erepo . "\n\n" . $urepo . "\n\n" . $drepo . "\n\n" . $sorepo . "\n\n" . $garepo);
        echo $functions;

        echo nl2br("\n\n//---------------------[ Functions Controller ]---------------------\n\n");

        $s = "public function Store_" . $models . "(" . $models . "Request \$request)
                    {
                    return \$this->" . $nameInterface . "->Store_" . $models . "(\$request);
                    }";

        $e = "public function Edit_" . $models . "(" . $models . "Request \$request)
                    {
                    return \$this->" . $nameInterface . "->Edit_" . $models . "(\$request);
                    }";

        $u = "public function Update_" . $models . "(" . $models . "Request \$request)
                    {
                    return \$this->" . $nameInterface . "->Update_" . $models . "(\$request);
                    }";

        $d = "public function Delete_" . $models . "(" . $models . "Request \$request)
                    {
                    return \$this->" . $nameInterface . "->Delete_" . $models . "(\$request);
                    }";

        $so = "public function Show_" . $models . "(" . $models . "Request \$request)
                    {
                    return \$this->" . $nameInterface . "->Show_" . $models . "(\$request);
                    }";

        $ga = "public function Get_All_" . $models . "s()
                    {
                    return \$this->" . $nameInterface . "->Get_All_" . $models . "();
                    }";

        $functionsController = nl2br($s . "\n\n" . $e . "\n\n" . $u . "\n\n" . $d . "\n\n" . $so . "\n\n" . $ga);

        echo $functionsController;
        echo nl2br("\n\n//---------------------[ Routes ]---------------------\n\n");

        $Controller_name = $nameInterface . "Controller";
        $pstart = nl2br("Route::prefix('" . strtolower($models) . "')->group(function () {");
        $sr = nl2br("Route::post('create',[" . $Controller_name . "::class,'Store_" . $models . "']);");
        $er = nl2br("Route::post('edit',[" . $Controller_name . "::class,'Edit_" . $models . "']);");
        $ur = nl2br("Route::post('update',[" . $Controller_name . "::class,'Update_" . $models . "']);");
        $dr = nl2br("Route::post('delete',[" . $Controller_name . "::class,'Delete_" . $models . "']);");
        $sor = nl2br("Route::post('show',[" . $Controller_name . "::class,'Show_" . $models . "']);");
        $gar = nl2br("Route::get('get_all',[" . $Controller_name . "::class,'Get_All_" . $models . "']);");
        $pend = nl2br("});");

        $Routes = nl2br($pstart . "\n" . $sr . "\n"
            . $er . "\n"
            . $ur . "\n"
            . $dr . "\n"
            . $sor . "\n"
            . $gar . "\n"
            . $pend . "\n");
        echo $Routes;
    }


    public function generating_with_custom_methods($models, $nameInterface, string|array $methods)
    {


        echo nl2br("\n\n//*********************[ " . $models . " ]*********************\n\n");
        echo nl2br("\n\n//---------------------[ Interfaces ]---------------------\n\n");
        if ('s' == $methods) {
            $si = nl2br("public function Store_" . $models . "(\$request);");
        }
        if ('e' == $methods) {
            $ei = nl2br("public function Edit_" . $models . "(\$request);");
        }
        if ('u' == $methods) {
            $ui = nl2br("public function Update_" . $models . "(\$request);");
        }
        if ('d' == $methods) {
            $di = nl2br("public function Delete_" . $models . "(\$request);");
        }
        if ('so' == $methods) {
            $soi = nl2br("public function Show_" . $models . "(\$request);");
        }
        if ('ga' == $methods) {
            $gai = nl2br("public function Get_All_" . $models . "s();");
        }


        $Interfaces = nl2br($si . "\n" . $ei . "\n" . $ui . "\n" . $di . "\n"
            . $soi . "\n"
            . $gai . "\n");
        echo $Interfaces;
        echo nl2br("\n\n//---------------------[ Functions Repository]---------------------\n\n");
        if ('s' == $methods) {
            $srepo = "public function Store_" . $models . "(\$request)
                    {
                    return \$this->CRUD_Create(" . $models . "::class,\$request);
                    }";
        }
        if ('e' == $methods) {
            $erepo = "public function Edit_" . $models . "(\$request)
                    {
                    return \$this->CRUD_Edit(" . $models . "::class,\$request);
                    }";
        }
        if ('u' == $methods) {
            $urepo = "public function Update_" . $models . "(\$request)
                    {
                    return \$this->CRUD_Update(" . $models . "::class,\$request);
                    }";
        }
        if ('d' == $methods) {
            $drepo = "public function Delete_" . $models . "(\$request)
                    {
                    return \$this->CRUD_Delete(" . $models . "::class,\$request);
                    }";
        }
        if ('so' == $methods) {
            $sorepo = "public function Show_" . $models . "(\$request)
                    {
                    return \$this->CRUD_Show(" . $models . "::class,\$request);
                    }";
        }
        if ('ga' == $methods) {
            $garepo = "public function Get_All_" . $models . "s()
                    {
                    return \$this->CRUD_Get_All(" . $models . "::class);
                    }";
        }
        $functions = nl2br($srepo . "\n\n" . $erepo . "\n\n" . $urepo . "\n\n" . $drepo . "\n\n" . $sorepo . "\n\n" . $garepo);
        echo $functions;

        echo nl2br("\n\n//---------------------[ Functions Controller ]---------------------\n\n");
        if ('s' == $methods) {
            $s = "public function Store_" . $models . "(" . $models . "Request \$request)
}
                    {
                    return \$this->" . $nameInterface . "->Store_" . $models . "(" . $models . "::class,\$request);
                    }";
        }
        if ('e' == $methods) {
            $e = "public function Edit_" . $models . "(" . $models . "Request \$request)
}
                    {
                    return \$this->" . $nameInterface . "->Edit_" . $models . "(" . $models . "::class,\$request);
                    }";
        }
        if ('u' == $methods) {
            $u = "public function Update_" . $models . "(" . $models . "Request \$request)
}
                    {
                    return \$this->" . $nameInterface . "->Update_" . $models . "(" . $models . "::class,\$request);
                    }";
        }
        if ('d' == $methods) {
            $d = "public function Delete_" . $models . "(" . $models . "Request \$request)
}
                    {
                    return \$this->" . $nameInterface . "->Delete_" . $models . "(" . $models . "::class,\$request);
                    }";
        }
        if ('so' == $methods) {
            $so = "public function Show_" . $models . "(" . $models . "Request \$request)
}
                    {
                    return \$this->" . $nameInterface . "->Show_" . $models . "(" . $models . "::class,\$request);
                    }";
        }
        if ('ga' == $methods) {

            $ga = "public function Get_All_" . $models . "s()
                    {
                    return \$this->" . $nameInterface . "->Get_All_" . $models . "(" . $models . "::class);
                    }";
        }

        $functionsController = nl2br($s . "\n\n" . $e . "\n\n" . $u . "\n\n" . $d . "\n\n" . $so . "\n\n" . $ga);

        echo $functionsController;
        echo nl2br("\n\n//---------------------[ Routes ]---------------------\n\n");

        $Controller_name = $nameInterface . "Controller";
        $pstart = nl2br("Route::prefix('" . strtolower($models) . "')->group(function () {");
        if ('s' == $methods) {
            $sr = nl2br("Route::post('create',[" . $Controller_name . "::class,'Store_" . $models . "']);");
        }
        if ('e' == $methods) {
            $er = nl2br("Route::post('edit',[" . $Controller_name . "::class,'Edit_" . $models . "']);");
        }
        if ('u' == $methods) {
            $ur = nl2br("Route::post('update',[" . $Controller_name . "::class,'Update_" . $models . "']);");
        }
        if ('d' == $methods) {
            $dr = nl2br("Route::post('delete',[" . $Controller_name . "::class,'Delete_" . $models . "']);");
        }
        if ('so' == $methods) {
            $sor = nl2br("Route::post('show',[" . $Controller_name . "::class,'Show_" . $models . "']);");
        }
        if ('ga' == $methods) {
            $gar = nl2br("Route::get('get_all',[" . $Controller_name . "::class,'Get_All_" . $models . "']);");
        }
        $pend = nl2br("});");

        $Routes = nl2br($pstart . "\n" . $sr . "\n"
            . $er . "\n"
            . $ur . "\n"
            . $dr . "\n"
            . $sor . "\n"
            . $gar . "\n"
            . $pend . "\n");
        echo $Routes;
    }


}
