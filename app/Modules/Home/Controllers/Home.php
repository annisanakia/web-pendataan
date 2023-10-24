<?php

namespace App\Modules\Home\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;

class Home extends Controller {

    protected $controller_name;
    protected $lib;

    public function __construct()
    {
        $this->middleware('auth', ['except' => ['form','store']]);
        $this->controller_name = 'Home';

        view::share('controller_name', strtolower($this->controller_name));
        try {
            parent::getHost();
        } catch (\Exception $e) { }
    }

    public function index()
    {
        $districts = \Models\district::all();
        
        $with['districts'] = $districts;
        return view($this->controller_name . '::index', $with);
    }

    public function form()
    {
        $action[] = array('name' => 'Simpan', 'type' => 'submit', 'url' => '#', 'class' => 'btn btn-success px-3 ms-md-1');

        $with['actions'] = $action;
        return view($this->controller_name . '::form', $with);
    }  

    public function store()
    {
        $user = \Auth::user();
        $input = request()->all();
        $model = new \Models\collection_data();
        $validation = $model->validate($input);
        $input['coordinator_id'] = $user->id ?? null;

        if ($validation->passes()) {
            unset($input['photo']);
            $input['photo'] = $this->store_image();
            $model->create($input);
            return Redirect::route('form')
                    ->with('success', 'Data berhasil disimpan!');
        }
        return Redirect::route('form')
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
}
