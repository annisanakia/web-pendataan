<?php

namespace App\Modules\Reference_data\Controllers;

use Models\reference_data as reference_dataModel;
use Lib\core\RESTful;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use File;

class Reference_data extends RESTful {

    public function __construct() {
        $model = new reference_dataModel;
        $controller_name = 'Reference_data';
        
        $this->table_name = 'reference_data';
        parent::__construct($model, $controller_name);
    }

    public function filterDistrict()
    {
        $user = \Auth::user();
        $globalTools = new \Lib\core\globalTools();

        $q = request()->city_id;
        $id = request()->id;

        $target = 'district_id';
        $blank = true;

        $datas = \Models\district::where('city_id', $q)
                ->orderBy('name', 'asc')->get()->pluck('name','id')->all();

        return $globalTools->renderList($datas, $target, $id, $blank);
    }

    public function filterSubdistrict()
    {
        $user = \Auth::user();
        $globalTools = new \Lib\core\globalTools();

        $q = request()->district_id;
        $id = request()->id;

        $target = 'subdistrict_id';
        $blank = true;

        $datas = \Models\subdistrict::where('district_id', $q)
                ->orderBy('name', 'asc')->get()->pluck('name','id')->all();

        return $globalTools->renderList($datas, $target, $id, $blank);
    }

    public function customFilter($data, $newFilters)
    {
        foreach ($newFilters as $key => $value) {
            if ($key == 'district_name') {
                $data->whereHas('district', function ($builder) use ($value){
                    $builder->where('name', 'like', '%' . $value . '%');
                });
            } elseif ($key == 'subdistrict_name') {
                $data->whereHas('subdistrict', function ($builder) use ($value){
                    $builder->where('name', 'like', '%' . $value . '%');
                });
            }
        }
    }

    public function update($id)
    {
        $input = $this->getParams(request()->all());
        $input['id'] = $id;
        $validation = $this->model->validate($input);

        if ($validation->passes()) {
            $data = $this->model->find($id);
            $data->update($input);
            return Redirect::route(strtolower($this->controller_name) . '.index');
        }
        return Redirect::route(strtolower($this->controller_name) . '.edit', $id)
            ->withInput()
            ->withErrors($validation)
            ->with('message', 'There were validation errors.');
    }
}
