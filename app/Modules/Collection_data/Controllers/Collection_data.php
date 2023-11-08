<?php

namespace App\Modules\Collection_data\Controllers;

use Models\collection_data as collection_dataModel;
use Lib\core\RESTful;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use File;
use App\Imports\generalImport;

class Collection_data extends RESTful {

    public function __construct() {
        $model = new collection_dataModel;
        $controller_name = 'Collection_data';
        
        $this->table_name = 'collection_data';
        $this->enable_xls = true;
        $this->enable_pdf = true;
        $this->enable_pdf_button = true;
        $this->enable_xls_button = true;
        $this->enable_import = true;
        parent::__construct($model, $controller_name);
    }

    public function beforeIndex($data)
    {
        $user_id = \Auth::user()->id ?? null;
        $groups_id = \Auth::user()->groups_id ?? null;
        if($groups_id == 2){
            $data->where('coordinator_id',$user_id);
        }
    }

    public function edit($id)
    {
        $user_id = \Auth::user()->id ?? null;
        $groups_id = \Auth::user()->groups_id ?? null;
        $data = $this->model->find($id);

        if (is_null($data) || ($user_id != $data->coordinator_id && $groups_id == 2)) {
            return Redirect::route(strtolower($this->controller_name) . '.index');
        }

        $action[] = array('name' => 'Batal', 'url' => strtolower($this->controller_name), 'class' => 'btn btn-secondary px-3 ms-md-1');
        if ($this->priv['delete_priv'])
            $action[] = array('name' => 'Hapus', 'url' => strtolower($this->controller_name) . '/delete/' . $id, 'class' => 'btn btn-danger px-3 ms-md-1 delete', 'attr' => 'ng-click=confirm($event) data-name='.strtoupper($data->name ?? $data->code));
        $action[] = array('name' => 'Simpan', 'type' => 'submit', 'url' => '#', 'class' => 'btn btn-success px-3 ms-md-1');

        $this->setAction($action);

        $content['data'] = $data;
        $content['actions'] = $this->actions;

        return View($this->controller_name . '::edit' , $content);
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
            if (request()->hasFile('photo')) {
                $input['photo'] = $this->store_image();
            }
            $data = $this->model->create($input);

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
        $user_id = \Auth::user()->id ?? null;
        $groups_id = \Auth::user()->groups_id ?? null;

        $input = $this->getParams(request()->all());
        $input['coordinator_id'] = request()->coordinator_id ?? ($groups_id == 2? $user_id : null);
        $input['id'] = $id;
        $validation = $this->model->validate($input);

        if ($validation->passes()) {
            unset($input['photo']);
            if (request()->hasFile('photo')) {
                $input['photo'] = $this->store_image();
            }
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
            } elseif ($key == 'user_name') {
                $data->whereHas('user', function ($builder) use ($value){
                    $builder->where('name', 'like', '%' . $value . '%');
                });
            } elseif ($key == 'datetime'){
                $data->where('activity_date','>=',date('Y-m-d H:i:00',strtotime($value)))
                    ->where('activity_date','<=',date('Y-m-d H:i:59',strtotime($value)));
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
        $data['title_col_sum'] = 20;

        if (request()->has('print_view')) {
            return view($template, $data);
        }

        return response(view($template, $data))
            ->header('Content-Type', 'application/vnd-ms-excel')
            ->header('Content-Disposition', 'attachment; filename="' . 'Data Pendataan ('.date('d-m-Y').').xls"');
    }

    public function delete($id)
    {
        $user_id = \Auth::user()->id ?? null;
        $groups_id = \Auth::user()->groups_id ?? null;
        $data = $this->model->find($id);

        if (is_null($data) || ($user_id != $data->coordinator_id && $groups_id == 2)) {
            return Redirect::route(strtolower($this->controller_name) . '.index');
        }
        if ($this->priv['delete_priv']) {
            $data = $this->model->find($id);
            if($data){
                $data->delete();
            }

            $user_id = \Auth::user()->id ?? null;
            $table_name = $this->model->getTable() ?? null;
            $data_id = $data->id ?? null;
            $activity_before = json_encode($data);
            $this->lib_activity->addActivity($user_id, $table_name, $id, 'delete', date('Y-m-d H:i:s'), null, $activity_before);
        }
        if (!request()->ajax()) {
            return Redirect::route(strtolower($this->controller_name) . '.index');
        }
    }

    public function getAutocomplete()
    {
        $data = \Models\reference_data::firstWhere('nik',request()->nik);
        return json_encode($data);
    }

    public function updateStatus($id)
    {
        $data = $this->model->find($id);
        $activity_before = json_encode($data);
        if(request()->status != ''){
            $data->status = request()->status;
        }elseif($data->status != 2){
            $data->status = 2;
        }
        $data->save();

        $user_id = \Auth::user()->id ?? null;
        $table_name = $this->model->getTable() ?? null;
        $data_id = $data->id ?? null;
        $activity_after = json_encode($data);
        $this->lib_activity->addActivity($user_id, $table_name, $id, 'updateStatus', date('Y-m-d H:i:s'), $activity_after, $activity_before);
        return redirect()->back();
    }

    public function updateStatusShare($id)
    {
        $data = $this->model->find($id);
        $activity_before = json_encode($data);
        if($data->status_share != 2 && $data->status == 2){
            $data->status_share = 2;
        }
        $data->save();

        $user_id = \Auth::user()->id ?? null;
        $table_name = $this->model->getTable() ?? null;
        $data_id = $data->id ?? null;
        $activity_after = json_encode($data);
        $this->lib_activity->addActivity($user_id, $table_name, $id, 'updateStatusShare', date('Y-m-d H:i:s'), $activity_after, $activity_before);
        return redirect()->back();
    }

    public function logActivity($id)
    {
        $table_name = $this->model->getTable() ?? null;
        $data = $this->model->find($id);

        $log_activitys = \Models\log_activity::where('object',$table_name)
                ->where('object_id',$id);
        $this->filter($log_activitys, request(), 'log_activity');
        $this->order($log_activitys, request());
        if (request()->has('max_row')) {
            $this->setMaxRow(request()->input('max_row'));
        }
        $log_activitys = $log_activitys->paginate($this->max_row);
        $log_activitys->chunk(100);

        $with['data'] = $data;
        $with['log_activitys'] = $log_activitys;
        $with['param'] = request()->all();
        return View($this->controller_name . '::logActivity' , $with);
    }

    public function import()
    {
        $with = [];
        return view($this->controller_name . '::import', $with);
    }

    public function getTemplateAsXls()
    {
        $template = $this->controller_name . '::getTemplateAsXls';
        $groups_id = \Auth::user()->groups_id ?? null;
        $data['groups_id'] = $groups_id;

        if (request()->has('print_view')) {
            return view($template, $data);
        }

        return response(view($template, $data))
            ->header('Content-Type', 'application/vnd-ms-excel')
            ->header('Content-Disposition', 'attachment; filename="' . 'Template Import Data Pendataan.xls"');
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

        $with['datas'] = array_slice($excel[0], 19, count($excel[0]));
        if($groups_id != 2){
            $with['datas'] = array_slice($excel[0], 21, count($excel[0]));
        }

        $with['groups_id'] = $groups_id;
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
        $whatsapp = is_array(request()->rw)? request()->whatsapp : [];
        $pob = is_array(request()->pob)? request()->pob : [];
        $dob = is_array(request()->dob)? request()->dob : [];
        $gender = is_array(request()->gender)? request()->gender : [];
        $religion_id = is_array(request()->religion_id)? request()->religion_id : [];
        $job_type_id = is_array(request()->job_type_id)? request()->job_type_id : [];
        $job_name = is_array(request()->job_name)? request()->job_name : [];
        $address = is_array(request()->address)? request()->address : [];
        $rt = is_array(request()->rt)? request()->rt : [];
        $rw = is_array(request()->rw)? request()->rw : [];
        $status = is_array(request()->status)? request()->status : [];
        $status_share = is_array(request()->status_share)? request()->status_share : [];
        $coordinator_id = is_array(request()->coordinator_id)? request()->coordinator_id : [];

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
                $input['whatsapp'] = $whatsapp[$key];
                $input['pob'] = $pob[$key];
                $input['dob'] = $dob[$key];
                $input['gender'] = $gender[$key];
                $input['religion_id'] = $religion_id[$key];
                $input['job_type_id'] = $job_type_id[$key];
                $input['job_name'] = $job_name[$key];
                $input['address'] = $address[$key];
                $input['rt'] = $rt[$key];
                $input['status'] = ($status[$key] ?? null) ?? ($groups_id == 2? 1 : null);;
                $input['status_share'] = ($status_share[$key] ?? null) ?? ($groups_id == 2? 1 : null);
                $input['coordinator_id'] = ($coordinator_id[$key] ?? null) ?? ($groups_id == 2? $user_id : null);
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
        $with['groups_id'] = $groups_id;
        $with['nik_duplicates'] = $nik_duplicates;
        $with['districts'] = $districts;
        $with['subdistricts'] = $subdistricts;
        return view($this->controller_name . '::previewValidation', $with)->withErrors($validation);
    }
}
