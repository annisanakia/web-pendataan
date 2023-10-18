<?php

namespace App\Modules\Subdistrict\Controllers;

use Models\subdistrict as subdistrictModel;
use Lib\core\RESTful;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use File;

class Subdistrict extends RESTful {

    public function __construct() {
        $model = new subdistrictModel;
        $controller_name = 'Subdistrict';
        
        $this->table_name = 'subdistrict';
        parent::__construct($model, $controller_name);
    }
}
