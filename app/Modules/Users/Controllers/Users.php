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
            $input['password'] = \Hash::make($input['password']);
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
            $data = $this->model->find($id);
            if ($input['password'] == '') {
                unset($input['password']);
            }else{
                $input['password'] = \Hash::make($input['password']);
            }
            
            $data->update($input);
            
            return Redirect::route(strtolower($this->controller_name) . '.index');
        }
        return Redirect::route(strtolower($this->controller_name) . '.edit', $id)
            ->withInput()
            ->withErrors($validation)
            ->with('message', 'There were validation errors.');
    }
}
