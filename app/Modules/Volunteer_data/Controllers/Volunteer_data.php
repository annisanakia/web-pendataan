<?php

namespace App\Modules\Volunteer_data\Controllers;

use Models\volunteer_data as volunteer_dataModel;
use Lib\core\RESTful;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use File;

class Volunteer_data extends RESTful {

    public function __construct() {
        $model = new volunteer_dataModel;
        $controller_name = 'Volunteer_data';
        
        $this->table_name = 'volunteer_data';
        parent::__construct($model, $controller_name);
    }

    public function beforeIndex($data)
    {
        $data->where('code','!=','DLL');
    }
}
