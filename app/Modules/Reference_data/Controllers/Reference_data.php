<?php

namespace App\Modules\Reference_data\Controllers;

use Models\reference_data as reference_dataModel;
use Lib\core\RESTful;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\PDF;

class Reference_data extends RESTful {

    public function __construct() {
        $model = new reference_dataModel;
        $controller_name = 'Reference_data';
        
        $this->table_name = 'reference_data';
        $this->enable_xls = true;
        $this->enable_pdf = true;
        $this->enable_pdf_button = true;
        $this->enable_xls_button = true;
        $this->enable_import = true;
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
    public function getListAsPdf()
    {
        $template = $this->controller_name . '::getListAsPdf';
        $data = $this->getList(request());
        $data['title_head_export'] = 'Data Referensi';

        $pdf = \PDF::loadView($template, $data)
            ->setPaper('legal', 'portrait');

        if (request()->has('print_view')) {
            return view($template, $data);
        }

        return $pdf->download('Data Referensi ('.date('d-m-Y').').pdf');
    }

    public function getListAsXls()
    {
        $template = $this->controller_name . '::getListAsXls';
        $data = $this->getList(request());
        $data['title_head_export'] = 'Data Referensi';
        $data['title_col_sum'] = 5;

        if (request()->has('print_view')) {
            return view($template, $data);
        }
        // return view($template, $data);

        return response(view($template, $data))
            ->header('Content-Type', 'application/vnd-ms-excel')
            ->header('Content-Disposition', 'attachment; filename="' . 'Data Referensi ('.date('d-m-Y').').xls"');
    }
}
