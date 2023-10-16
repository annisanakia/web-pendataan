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

        if ($validation->passes()) {
            unset($input['url_photo']);
            if (request()->hasFile('url_photo')) {
                $image = request()->file('url_photo');
                $imagename = date('ymd') . time() . '.' . $image->getClientOriginalExtension();
                $destinationPath = public_path('assets/file/users');

                if (!file_exists($destinationPath)) {
                    File::makeDirectory($destinationPath, $mode = 0777, true, true);
                }

                $image->move($destinationPath, $imagename);
                $path = request()->getSchemeAndHttpHost() . '/assets/file/users/' . $imagename;
                $input['url_photo'] = $path;
            }
            
            $input['password'] = \Hash::make($input['password']);
            $input['registration_code'] = request()->groups_id == 3? request()->registration_code : null;
            $input['employee_code'] = in_array(request()->groups_id,[1,2])? request()->employee_code : null;
            $data = $this->model->create($input);

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
        
        if ($validation->passes()) {
            unset($input['url_photo']);
            if (request()->hasFile('url_photo')) {
                $image = request()->file('url_photo');
                $imagename = date('ymd') . time() . '.' . $image->getClientOriginalExtension();
                $destinationPath = public_path('assets/file/users');

                if (!file_exists($destinationPath)) {
                    File::makeDirectory($destinationPath, $mode = 0777, true, true);
                }

                $image->move($destinationPath, $imagename);
                $path = request()->getSchemeAndHttpHost() . '/assets/file/users/' . $imagename;
                $input['url_photo'] = $path;
            }

            $data = $this->model->find($id);
            if ($input['password'] == '') {
                unset($input['password']);
            }else{
                $input['password'] = \Hash::make($input['password']);
            }
            $input['registration_code'] = request()->groups_id == 3? request()->registration_code : null;
            $input['employee_code'] = in_array(request()->groups_id,[1,2])? request()->employee_code : null;
            
            $data->update($input);
            
            return Redirect::route(strtolower($this->controller_name) . '.index');
        }
        return Redirect::route(strtolower($this->controller_name) . '.edit', $id)
            ->withInput()
            ->withErrors($validation)
            ->with('message', 'There were validation errors.');
    }

    public function delete_img($id)
    {
        if ($this->priv['delete_priv']) {
            $data = $this->model->find($id);
            if($data){
                $image_name = $data->url_photo;
                $image_path = public_path($data->url_photo);

                if(file_exists($image_path)){
                    unlink($image_path);
                }

                $data->url_photo = null;
                $data->save();
            }
        }
        if (!request()->ajax()) {
            return Redirect::route(strtolower($this->controller_name) . '.index');
        }
    }

    public function customFilter($data, $newFilters)
    {
        foreach ($newFilters as $key => $value) {
            if ($key == 'detail_user') {
                $data->where(function ($query) use ($value){
                    $query->where('name', 'like', '%' . $value . '%')
                        ->orWhere('registration_code', 'like', '%' . $value . '%')
                        ->orWhere('employee_code', 'like', '%' . $value . '%');
                });
            }
        }
    }
}
