<?php

namespace App\Modules\Users\Controllers;

use App\Models\User as userModel;
use Lib\core\RESTful;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use File;
use App\Imports\generalImport;

class Users extends RESTful {

    public function __construct() {
        $model = new userModel;
        $controller_name = 'Users';
        
        $this->enable_import = true;
        $this->table_name = 'users';
        parent::__construct($model, $controller_name);
    }

    public function store()
    {
        $input = Request()->all();
        $validation = $this->model->validate($input);

        unset($input['subdistrict_ids']);

        if ($validation->passes()) {
            $input['password'] = \Hash::make($input['password']);
            $data = $this->model->create($input);

            $model = new \Models\users_subdistrict();
            $users_subdistrict_ids = [];
            if(in_array($data->groups_id,[2,3])){
                $users_subdistricts = $model->where('user_id',$data->id)->get()->keyBy('subdistrict_id')->all();
                $subdistrict_ids = is_array(request()->subdistrict_ids)? request()->subdistrict_ids : [];
                $input_subdistrict['user_id'] = $data->id;
                foreach($subdistrict_ids as $subdistrict_id){
                    $input_subdistrict['subdistrict_id'] = $subdistrict_id;
                    $users_subdistrict = $users_subdistricts[$subdistrict_id] ?? null;

                    if(!$users_subdistrict){
                        $users_subdistrict = $model->create($input_subdistrict);
                    }else{
                        $users_subdistrict->update($input_subdistrict);
                    }

                    $users_subdistrict = $model->create($input_subdistrict);
                    $users_subdistrict_ids[] = $users_subdistrict->id;
                }
            }
            $users_subdistrict_delete = $model->where('user_id',$data->id)
                    ->whereNotIn('id',$users_subdistrict_ids)->delete();

            $user_id = \Auth::user()->id ?? null;
            $table_name = $this->model->getTable() ?? null;
            $data_id = $data->id ?? null;
            $activity_after = json_encode($data);
            $this->lib_activity->addActivity($user_id, $table_name, $data_id, 'store', date('Y-m-d H:i:s'), $activity_after);

            return Redirect::route(strtolower($this->controller_name) . '.index');
        }

        return Redirect::route(strtolower($this->controller_name) . '.create')
            ->withInput(request()->all())
            ->withErrors($validation)
            ->with('message', 'There were validation errors.');
    }

    public function update($id)
    {
        $input = Request()->all();
        $input['id'] = $id;

        $validation = $this->model->validate($input);
        // unset($input['subdistrict_ids']);
        
        if ($validation->passes()) {
            $data = $this->model->find($id);
            if ($input['password'] == '') {
                unset($input['password']);
            }else{
                $input['password'] = \Hash::make($input['password']);
            }
            
            $activity_before = json_encode($data);
            $data->update($input);

            $model = new \Models\users_subdistrict();
            $users_subdistrict_ids = [];
            if(in_array($data->groups_id,[2,3])){
                $users_subdistricts = $model->where('user_id',$id)->get()->keyBy('subdistrict_id')->all();
                $subdistrict_ids = is_array(request()->subdistrict_ids)? request()->subdistrict_ids : [];
                $input_subdistrict['user_id'] = $id;
                foreach($subdistrict_ids as $subdistrict_id){
                    $input_subdistrict['subdistrict_id'] = $subdistrict_id;
                    $users_subdistrict = $users_subdistricts[$subdistrict_id] ?? null;

                    if(!$users_subdistrict){
                        $users_subdistrict = $model->create($input_subdistrict);
                    }else{
                        $users_subdistrict->update($input_subdistrict);
                    }

                    $users_subdistrict_ids[] = $users_subdistrict->id;
                }
            }
            $users_subdistrict_delete = $model->where('user_id',$id)
                    ->whereNotIn('id',$users_subdistrict_ids)->delete();

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

    public function delete($id)
    {
        if ($this->priv['delete_priv']) {
            $data = $this->model->find($id);
            if($data){
                $data->delete();
                $users_subdistrict_delete = \Models\users_subdistrict::where('user_id',$id)->delete();
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

    public function import()
    {
        if ($this->disabled_add) {
            return Redirect::route(strtolower($this->controller_name) . '.index');
        }
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
            ->header('Content-Disposition', 'attachment; filename="' . 'Template Import Data Pengguna.xls"');
    }

    public function previewImport()
    {
        $file = request()->file('file');
        $user_id = \Auth::user()->id ?? null;
        $groups_id = \Auth::user()->groups_id ?? null;

        $excel = \Excel::toArray(new generalImport(), $file);

        $subdistricts = \Models\subdistrict::select(['id','name','district_id','code'])->with(['district'])->get()
                ->groupBy('district_id')->all();
        $last_educations = \Models\last_education::select(['code','id','name'])->get();
        $groups = \Models\groups::select(['code','id','name'])->get();

        $with['datas'] = array_slice($excel[0], 15, count($excel[0]));

        $with['groups_id'] = $groups_id;
        $with['subdistricts'] = $subdistricts;
        $with['last_educations'] = $last_educations;
        $with['groups'] = $groups;
        return view($this->controller_name . '::previewImport', $with);
    }

    public function storeImport()
    {
        $user_id = \Auth::user()->id ?? null;
        $names = is_array(request()->name)? request()->name : [];
        $groups_id = is_array(request()->groups_id)? request()->groups_id : [];
        $email = is_array(request()->email)? request()->email : [];
        $phone_no = is_array(request()->phone_no)? request()->phone_no : [];
        $pob = is_array(request()->pob)? request()->pob : [];
        $dob = is_array(request()->dob)? request()->dob : [];
        $address = is_array(request()->address)? request()->address : [];
        $last_education_id = is_array(request()->last_education_id)? request()->last_education_id : [];
        $subdistrict_id = is_array(request()->subdistrict_id)? request()->subdistrict_id : [];
        $no_tps = is_array(request()->no_tps)? request()->no_tps : [];
        $status = is_array(request()->status)? request()->status : [];
        
        $input = request()->all();
        $validation = $this->model->validateMultiple($input);

        // email double input
        $email_unique = array_unique($email);
        $check = count($email) !== count($email_unique);
        $email_duplicates = [];
        if($check == 1) {
            //Duplicates found
            $email_duplicates = array_diff_assoc($email, $email_unique);
        }
        foreach($email_duplicates as $key => $email){
            $validation->getMessageBag()->add('email.'.$key, 'Terdapat lebih dari 1 data import email yang sama');
        }

        $subdistricts = \Models\subdistrict::select(['id','code'])->pluck('code','id')->all();

        if ($validation->errors()->count() <= 0) {
            $input = [];
            $usernames = [];
            foreach($names as $key => $name){
                $words = strtolower(getFirstChar($name));
                $tps = 'tps'.ltrim($no_tps[$key], "0");
                $subdistrict_code = strtolower($subdistricts[$subdistrict_id[$key]] ?? null);
                $dob_number = date('dmY',strtotime($dob[$key]));
                $sequence = sprintf('%04d', ($usernames[$subdistrict_code.'_'.$tps] ?? 0)+1);
                $username = $words.'_'.$subdistrict_code.'_'.$tps.'_'.$sequence;

                if(array_key_exists(($subdistrict_code.'_'.$tps),$usernames)){
                    $usernames[$subdistrict_code.'_'.$tps] += 1; 
                }else{
                    $usernames[$subdistrict_code.'_'.$tps] = 1; 
                }

                $input['code'] = $username;
                $input['username'] = $username;
                $input['password'] = $dob_number;

                $input['name'] = $name;
                $input['groups_id'] = $groups_id[$key];
                $input['email'] = $email[$key];
                $input['phone_no'] = $phone_no[$key];
                $input['pob'] = $pob[$key];
                $input['dob'] = $dob[$key];
                $input['address'] = $address[$key];
                $input['last_education_id'] = $last_education_id[$key];
                $input['subdistrict_id'] = $subdistrict_id[$key];
                $input['no_tps'] = $no_tps[$key];
                $input['status'] = $status[$key] ?? 1;

                $data = $this->model->create($input);
            }

            \Session::flash('message_import', 'Data Import telah berhasil di upload!'); 
            return redirect(strtolower($this->controller_name));
        }

        $subdistricts = \Models\subdistrict::select(['id','name','district_id','code'])->with(['district'])->get()
                ->groupBy('district_id')->all();
        $last_educations = \Models\last_education::select(['code','id','name'])->get();
        $groups = \Models\groups::select(['code','id','name'])->get();

        $with = request()->all();
        $with['email_duplicates'] = $email_duplicates;
        $with['subdistricts'] = $subdistricts;
        $with['last_educations'] = $last_educations;
        $with['groups'] = $groups;
        return view($this->controller_name . '::previewValidation', $with)->withErrors($validation);
    }
}
