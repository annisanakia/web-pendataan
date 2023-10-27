<?php

namespace App\Modules\Collection_data\Controllers;

use Models\collection_data as collection_dataModel;
use Lib\core\RESTful;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use File;

class Collection_data extends RESTful {

    public function __construct() {
        $model = new collection_dataModel;
        $controller_name = 'Collection_data';
        
        $this->table_name = 'collection_data';
        $this->enable_xls = true;
        $this->enable_pdf = true;
        $this->enable_pdf_button = true;
        $this->enable_xls_button = true;
        parent::__construct($model, $controller_name);
    }

    public function store()
    {
        $user_id = \Auth::user()->id ?? null;
        $groups_id = \Auth::user()->groups_id ?? null;
        
        $input = $this->getParams(request()->all());
        $input['coordinator_id'] = request()->coordinator_id ?? ($groups_id == 2? $user_id : null);
        $validation = $this->model->validate($input);

        if ($validation->passes()) {
            unset($input['photo']);
            $input['photo'] = $this->store_image();
            $data = $this->model->create($input);

            $table_name = $this->model->getTable() ?? null;
            $data_id = $data->id ?? null;
            $activity_after = json_encode($data);
            $this->lib_activity->addActivity($user_id, $table_name, $data_id, 'insert', date('Y-m-d H:i:s'), $activity_after);

            return Redirect::route(strtolower($this->controller_name) . '.index');
        }

        return Redirect::route(strtolower($this->controller_name) . '.create')
            ->withInput()
            ->withErrors($validation)
            ->with('message', 'There were validation errors.');
    }
    
    public function update($id)
    {
        $user_id = \Auth::user()->id ?? null;
        $groups_id = \Auth::user()->groups_id ?? null;

        $input = $this->getParams(request()->all());
        $input['coordinator_id'] = request()->coordinator_id ?? ($groups_id == 2? $user_id : null);
        $input['id'] = $id;
        $validation = $this->model->validate($input);

        if ($validation->passes()) {
            unset($input['photo']);
            $input['photo'] = $this->store_image();
            $data = $this->model->find($id);
            $activity_before = json_encode($data);

            $data->update($input);

            $table_name = $this->model->getTable() ?? null;
            $data_id = $data->id ?? null;
            $activity_after = json_encode($data);
            $this->lib_activity->addActivity($user_id, $table_name, $data_id, 'update', date('Y-m-d H:i:s'), $activity_after, $activity_before);

            return Redirect::route(strtolower($this->controller_name) . '.index');
        }
        return Redirect::route(strtolower($this->controller_name) . '.edit', $id)
            ->withInput()
            ->withErrors($validation)
            ->with('message', 'There were validation errors.');
    }

    public function store_image()
    {
        $url = null;
        if (request()->hasFile('photo')) {
            $image = request()->file('photo');
            $imagename = date('ymd') . time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('assets/file/ktp');

            if (!file_exists($destinationPath)) {
                File::makeDirectory($destinationPath, $mode = 0777, true, true);
            }

            $image->move($destinationPath, $imagename);
            $path = request()->getSchemeAndHttpHost() . '/assets/file/ktp/' . $imagename;
            $url = $path;
        }

        return $url;
    }

    public function delete_img($id)
    {
        if ($this->priv['delete_priv']) {
            $data = $this->model->find($id);
            $activity_before = json_encode($data);
            if($data){
                $image_name = $data->photo;
                $image_path = public_path($data->photo);

                if(file_exists($image_path)){
                    unlink($image_path);
                }

                $data->photo = null;
                $data->save();
            }
        }

        $user_id = \Auth::user()->id ?? null;
        $table_name = $this->model->getTable() ?? null;
        $data_id = $data->id ?? null;
        $activity_after = json_encode($data);
        $this->lib_activity->addActivity($user_id, $table_name, $id, 'delete_img', date('Y-m-d H:i:s'), $activity_after, $activity_before);

        if (!request()->ajax()) {
            return Redirect::route(strtolower($this->controller_name) . '.index');
        }
    }

    public function customFilter($data, $newFilters)
    {
        foreach ($newFilters as $key => $value) {
            if ($key == 'coordinator_name') {
                $data->whereHas('coordinator', function ($builder) use ($value){
                    $builder->where('name', 'like', '%' . $value . '%');
                });
            } elseif ($key == 'subdistrict_name') {
                $data->whereHas('subdistrict', function ($builder) use ($value){
                    $builder->where('name', 'like', '%' . $value . '%');
                });
            }
        }
    }
    
    public function getListAsPdf()
    {
        $template = $this->controller_name . '::getListAsPdf';
        $data = $this->getList(request());
        $data['title_head_export'] = 'Data Pendataan';

        $pdf = \PDF::loadView($template, $data)
            ->setPaper('legal', 'portrait');

        if (request()->has('print_view')) {
            return view($template, $data);
        }

        return $pdf->download('Data Pendataan ('.date('d-m-Y').').pdf');
    }

    public function getListAsXls()
    {
        $template = $this->controller_name . '::getListAsXls';
        $data = $this->getList(request());
        $data['title_head_export'] = 'Data Pendataan';
        $data['title_col_sum'] = 9;

        if (request()->has('print_view')) {
            return view($template, $data);
        }
        // return view($template, $data);

        return response(view($template, $data))
            ->header('Content-Type', 'application/vnd-ms-excel')
            ->header('Content-Disposition', 'attachment; filename="' . 'Data Pendataan ('.date('d-m-Y').').xls"');
    }

    public function getAutocomplete()
    {
        $data = \Models\reference_data::firstWhere('nik',request()->nik);
        return json_encode($data);
    }
    
}
