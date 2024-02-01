<?php

namespace App\Modules\Users\Controllers;

use App\Models\User as userModel;
use Lib\core\RESTful;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use File;

class Users extends RESTful {

    public function __construct() {
        $model = new userModel;
        $controller_name = 'Users';
        
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
}
