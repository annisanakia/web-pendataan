<?php

namespace App\Modules\Home\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class Home extends Controller {

    protected $controller_name;
    protected $lib;

    public function __construct()
    {
        $this->middleware('auth');
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
}
