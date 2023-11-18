<?php

namespace App\Modules\Election_results\Controllers;

use Models\election_results as election_resultsModel;
use Lib\core\RESTful;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use PDF;
use File;

class Election_results extends RESTful {

    public function __construct() {
        $model = new election_resultsModel;
        $controller_name = 'Election_results';
        
        $this->table_name = 'election_results';
        $this->enable_xls = true;
        $this->enable_pdf = true;
        $this->enable_pdf_button = true;
        $this->enable_xls_button = true;
        parent::__construct($model, $controller_name);
    }

    public function store()
    {
        $input = $this->getParams(request()->all());
        $validation = $this->model->validate($input);

        $data = $this->model->where('city_id',request()->city_id)
                    ->where('district_id',request()->district_id)
                    ->where('subdistrict_id',request()->subdistrict_id)
                    ->where('no_tps',request()->no_tps)
                    ->first();

        if($data){
            $district = \Models\district::find(request()->district_id);
            $subdistrict = \Models\subdistrict::find(request()->subdistrict_id);
            \Session::flash('message_error', 'Data dengan kecamatan <b>'.($district->name).'</b>, kelurahan <b>'.($subdistrict->name).'</b>, dan no TPS <b>'.request()->no_tps.'</b> telah tersedia.');
            return Redirect::route(strtolower($this->controller_name) . '.create')
                ->withInput()
                ->withErrors($validation)
                ->with('message', 'There were validation errors.');
        }

        if ($validation->passes()) {
            $data = $this->model->create($input);

            $files = is_array(request()->file('url_file'))? request()->file('url_file') : [];
            $sequence = 0;
            foreach($files as $file){
                $input['election_results_id'] = $data->id;
                $input['url_file'] = $this->store_image($file,++$sequence);
                $election_results_file = $model_file->create($input);
            }

            $user_id = \Auth::user()->id ?? null;
            $table_name = $this->model->getTable() ?? null;
            $data_id = $data->id ?? null;
            $activity_after = json_encode($data);
            $this->lib_activity->addActivity($user_id, $table_name, $data_id, 'store', date('Y-m-d H:i:s'), $activity_after);

            return Redirect::route(strtolower($this->controller_name) . '.index');
        }
        return Redirect::route(strtolower($this->controller_name) . '.create')
            ->withInput()
            ->withErrors($validation)
            ->with('message', 'There were validation errors.');
    }

    public function update($id)
    {
        $input = $this->getParams(request()->all());
        $validation = $this->model->validate($input);

        $data = $this->model->where('city_id',request()->city_id)
                    ->where('district_id',request()->district_id)
                    ->where('subdistrict_id',request()->subdistrict_id)
                    ->where('no_tps',request()->no_tps)
                    ->where('id','!=',$id)
                    ->first();

        if($data){
            $district = \Models\district::find(request()->district_id);
            $subdistrict = \Models\subdistrict::find(request()->subdistrict_id);
            \Session::flash('message_error', 'Data dengan kecamatan <b>'.($district->name).'</b>, kelurahan <b>'.($subdistrict->name).'</b>, dan no TPS <b>'.request()->no_tps.'</b> telah tersedia.');
            return Redirect::route(strtolower($this->controller_name) . '.edit', $id)
                ->withInput()
                ->withErrors($validation)
                ->with('message', 'There were validation errors.');
        }

        if ($validation->passes()) {
            $data = $this->model->find($id);
            $activity_before = json_encode($data);

            $data->update($input);

            $model = new \Models\election_results_file();
            $files = is_array(request()->file('url_file'))? request()->file('url_file') : [];
            $file_keys = is_array(request()->file_key)? request()->file_key : [];
            $election_results_file_ids = is_array(request()->election_results_file_id)? request()->election_results_file_id : [];
            
            $file_ids = [];
            $sequence = 0;
            foreach($file_keys as $key => $file_key){
                $file = $files[$key] ?? null;
                $election_results_file_id = $election_results_file_ids[$key] ?? null;
                if($file || $election_results_file_id){
                    $election_results_file = \Models\election_results_file::find($election_results_file_id);
                    if($file){
                        $input['election_results_id'] = $id;
                        $input['url_file'] = $this->store_image($file,++$sequence);
                        $election_results_file = $model->create($input);
                    }
                    $file_ids[] = $election_results_file->id ?? null;
                }
            }
            $election_results_file_deletes = \Models\election_results_file::where('election_results_id',$id)
                ->whereNotIn('id',$file_ids)
                ->delete();

            $user_id = \Auth::user()->id ?? null;
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

    public function store_image($url_file,$sequence = 1)
    {
        $url = null;
        if ($url_file) {
            $image = $url_file;
            $imagename = date('ymd') . time() . sprintf('%02d', $sequence) . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('assets/file/result');

            if (!file_exists($destinationPath)) {
                File::makeDirectory($destinationPath, $mode = 0777, true, true);
            }

            $image->move($destinationPath, $imagename);
            $url = request()->getSchemeAndHttpHost() . '/assets/file/result/' . $imagename;
        }

        return $url;
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
    
    public function getListAsPdf()
    {
        $template = $this->controller_name . '::getListAsPdf';
        $data = $this->getList(request());
        $data['title_head_export'] = 'Data Hasil Pemilu';

        $pdf = \PDF::loadView($template, $data)
            ->setPaper('A4', 'portrait');

        if (request()->has('print_view')) {
            return view($template, $data);
        }

        return $pdf->download('Data Hasil Pemilu ('.date('d-m-Y').').pdf');
    }

    public function getListAsXls()
    {
        $template = $this->controller_name . '::getListAsXls';
        $data = $this->getList(request());
        $data['title_head_export'] = 'Data Hasil Pemilu';
        $data['title_col_sum'] = 5;

        if (request()->has('print_view')) {
            return view($template, $data);
        }
        // return view($template, $data);

        return response(view($template, $data))
            ->header('Content-Type', 'application/vnd-ms-excel')
            ->header('Content-Disposition', 'attachment; filename="' . 'Data Hasil Pemilu ('.date('d-m-Y').').xls"');
    }
}
