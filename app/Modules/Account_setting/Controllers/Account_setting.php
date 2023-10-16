<?php

namespace App\Modules\Account_setting\Controllers;

use App\Models\User as userModel;
use Lib\core\RESTful;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\File as FileImage;
use File;

class Account_setting extends RESTful {

    public function __construct() {
        $model = new userModel;
        $controller_name = 'Account_setting';

        $this->table_name = 'users';
        parent::__construct($model, $controller_name);
    }

    public function index(){
        $action[] = array('name' => 'Simpan Perubahan', 'type' => 'submit', 'url' => '#', 'class' => 'btn btn-outline-success px-3');
        $this->setAction($action);

        $with['actions'] = $this->actions;
        $with['data'] = \Auth::user();
        return View($this->controller_name . '::index', $with);
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

    public function update($id)
    {
        $input = Request()->all();
        $input['id'] = $id;

        $rules = array(
            'name' => 'required',
            'email' => 'email|nullable',
            'phone_no' => 'numeric|nullable|min_digits:10'
        );
    
        $customMessages = [
            'required' => 'Kolom ini wajib diisi.',
            'email' => 'Format alamat email salah.',
            'phone_no.numeric' => 'Nomor telepon harus berupa angka.',
            'phone_no.min_digits' => 'Masukkan nomor telepon minimal 10 angka.'
        ];

        $validation = Validator::make($input, $rules, $customMessages);
        
        if ($validation->passes()) {
            $data = $this->model->find($id);

            $data->update($input);
            
            return Redirect::route(strtolower($this->controller_name) . '.index')
                    ->with('type', '1')
                    ->with('success', '1');
        }
        return Redirect::route(strtolower($this->controller_name) . '.index')
            ->withInput()
            ->withErrors($validation)
            ->with('type', '1');
    }

    public function update_password($id)
    {

        $input = request()->all();
        $data = $this->model->find($id);

        $current_password = request()->current_password;

        $rules = array(
            'password' => 'required|min:6',
            'password_confirmation' => 'required|same:password',
            'current_password' => ['required', function ($atribute, $value, $fail) use ($data) {
                if (!\Hash::check($value, $data->password)) {
                    return $fail(__('Kata sandi saat ini salah.'));
                }
            }]
        );
    
        $customMessages = [
            'required' => 'Kolom ini wajib diisi.',
            'password_confirmation.same' => 'Konfirmasi kata sandi tidak cocok.',
            'password.min' => 'Masukkan password minimal 6 karakter.'
        ];

        $validation = Validator::make($input, $rules, $customMessages);
        
        if ($validation->passes()) {
            $data->password = \Hash::make(request()->password);
            $data->save();
            return Redirect::route(strtolower($this->controller_name) . '.index')
                    ->with('type', '2')
                    ->with('success', '1');
        }
        // dd($validation,$validation->passes(),$validation->messages());
        return Redirect::route(strtolower($this->controller_name) . '.index')
            ->withInput()
            ->withErrors($validation)
            ->with('type', '2');
    }
}
