<?php

namespace App\Modules\Reference_data\Controllers;

use Models\reference_data as reference_dataModel;
use Lib\core\RESTful;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use PDF;
use App\Imports\generalImport;

class Reference_data extends RESTful {

    public function __construct() {
        $model = new reference_dataModel;
        $controller_name = 'Reference_data';
        
        $this->setExceptMiddleware(['filterDistrict', 'filterSubdistrict']);
        
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
        $globalTools = new \Lib\core\globalTools();

        $q = request()->city_id;
        $id = request()->id;

        $target = 'district_id';
        $blank = true;

        $user = \Auth::user();
        $datas = \Models\district::where('city_id', $q);
        if($user->groups_id == 2){
            $subdistrict_ids = \Models\users_subdistrict::where('user_id',$user->id ?? null)->pluck('subdistrict_id')->all();
            $datas->whereHas('subdistrict', function($builder) use($subdistrict_ids){
                $builder->whereIn('id',$subdistrict_ids);
            });
        }
        $datas = $datas->orderBy('name', 'asc')->get()->pluck('name','id')->all();

        return $globalTools->renderList($datas, $target, $id, $blank);
    }

    public function filterSubdistrict()
    {
        $globalTools = new \Lib\core\globalTools();

        $q = request()->district_id;
        $id = request()->id;

        $target = 'subdistrict_id';
        $blank = true;

        $user = \Auth::user();
        $datas = \Models\subdistrict::where('district_id', $q);
        if($user->groups_id == 2){
            $subdistrict_ids = \Models\users_subdistrict::where('user_id',$user->id ?? null)->pluck('subdistrict_id')->all();
            $datas->whereIn('id',$subdistrict_ids);
        }
        $datas = $datas->orderBy('name', 'asc')->get()->pluck('name','id')->all();

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
            ->setPaper('A4', 'portrait');

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
        $data['title_col_sum'] = 12;

        if (request()->has('print_view')) {
            return view($template, $data);
        }

        return response(view($template, $data))
            ->header('Content-Type', 'application/vnd-ms-excel')
            ->header('Content-Disposition', 'attachment; filename="' . 'Data Referensi ('.date('d-m-Y').').xls"');
    }

    public function import()
    {
        $with = [];
        return view($this->controller_name . '::import', $with);
    }

    public function getTemplateAsXls()
    {
        $template = $this->controller_name . '::getTemplateAsXls';
        $data = [];

        if (request()->has('print_view')) {
            return view($template, $data);
        }

        return response(view($template, $data))
            ->header('Content-Type', 'application/vnd-ms-excel')
            ->header('Content-Disposition', 'attachment; filename="' . 'Template Import Data Referensi.xls"');
    }

    public function previewImport()
    {
        $file = request()->file('file');
        $user_id = \Auth::user()->id ?? null;
        $groups_id = \Auth::user()->groups_id ?? null;

        $excel = \Excel::toArray(new generalImport(), $file);

        $subdistrict_ids = \Models\users_subdistrict::select(['subdistrict_id'])
                ->where('user_id',$user_id)
                ->pluck('subdistrict_id')
                ->all();

        $districts = \Models\district::select(['*']);
        if($groups_id == 2){
            $districts->whereHas('subdistrict', function($builder) use($subdistrict_ids){
                $builder->whereIn('id',$subdistrict_ids);
            });
        }
        $districts = $districts->get();
        $district_codes = $districts->pluck('id','code')->all();

        $subdistricts = \Models\subdistrict::select(['*']);
        if($groups_id == 2){
            $subdistricts->whereIn('id',$subdistrict_ids);
        }
        $subdistricts = $subdistricts->get();
        $subdistrict_codes = $subdistricts->pluck('id','code')->all();

        $with['datas'] = array_slice($excel[0], 18, count($excel[0]));
        $with['district_codes'] = $district_codes;
        $with['subdistrict_codes'] = $subdistrict_codes;
        $with['districts'] = $districts;
        $with['subdistricts'] = $subdistricts;
        return view($this->controller_name . '::previewImport', $with);
    }

    public function storeImport()
    {
        $user_id = \Auth::user()->id ?? null;
        $groups_id = \Auth::user()->groups_id ?? null;
        $niks = is_array(request()->nik)? request()->nik : [];
        $name = is_array(request()->name)? request()->name : [];
        $city_id = is_array(request()->city_id)? request()->city_id : [];
        $district_id = is_array(request()->district_id)? request()->district_id : [];
        $subdistrict_id = is_array(request()->subdistrict_id)? request()->subdistrict_id : [];
        $no_tps = is_array(request()->no_tps)? request()->no_tps : [];
        $pob = is_array(request()->pob)? request()->pob : [];
        $dob = is_array(request()->dob)? request()->dob : [];
        $gender = is_array(request()->gender)? request()->gender : [];
        $religion_id = is_array(request()->religion_id)? request()->religion_id : [];
        $job_type_id = is_array(request()->job_type_id)? request()->job_type_id : [];
        $job_name = is_array(request()->job_name)? request()->job_name : [];
        $address = is_array(request()->address)? request()->address : [];
        $rt = is_array(request()->rt)? request()->rt : [];
        $rw = is_array(request()->rw)? request()->rw : [];

        $input = request()->all();
        $validation = $this->model->validateMultiple($input);

        // nik double input
        $nik_unique = array_unique($niks);
        $check = count($niks) !== count($nik_unique);
        $nik_duplicates = [];
        if($check == 1) {
            //Duplicates found
            $nik_duplicates = array_diff_assoc($niks, $nik_unique);
        }
        foreach($nik_duplicates as $key => $nik){
            $validation->getMessageBag()->add('nik.'.$key, 'Terdapat lebih dari 1 data import NIK yang sama');
        }

        if ($validation->errors()->count() <= 0) {
            $input = [];
            foreach($niks as $key => $nik){
                $input['nik'] = $nik;
                $input['name'] = $name[$key];
                $input['city_id'] = $city_id[$key];
                $input['district_id'] = $district_id[$key];
                $input['subdistrict_id'] = $subdistrict_id[$key];
                $input['no_tps'] = $no_tps[$key];
                $input['pob'] = $pob[$key];
                $input['dob'] = $dob[$key];
                $input['gender'] = $gender[$key];
                $input['religion_id'] = $religion_id[$key];
                $input['job_type_id'] = $job_type_id[$key];
                $input['job_name'] = $job_name[$key];
                $input['address'] = $address[$key];
                $input['rt'] = $rt[$key];
                $input['rw'] = $rw[$key];
                $data = $this->model->create($input);
            }

            \Session::flash('message_import', 'Data Import telah berhasil di upload!'); 
            return redirect(strtolower($this->controller_name));
        }

        $subdistrict_ids = \Models\users_subdistrict::select(['subdistrict_id'])
                ->where('user_id',$user_id)
                ->pluck('subdistrict_id')
                ->all();

        $districts = \Models\district::select(['*']);
        if($groups_id == 2){
            $districts->whereHas('subdistrict', function($builder) use($subdistrict_ids){
                $builder->whereIn('id',$subdistrict_ids);
            });
        }
        $districts = $districts->get();

        $subdistricts = \Models\subdistrict::select(['*']);
        if($groups_id == 2){
            $subdistricts->whereIn('id',$subdistrict_ids);
        }
        $subdistricts = $subdistricts->get();

        $with = request()->all();
        $with['nik_duplicates'] = $nik_duplicates;
        $with['districts'] = $districts;
        $with['subdistricts'] = $subdistricts;
        return view($this->controller_name . '::previewValidation', $with)->withErrors($validation);
    }
}
