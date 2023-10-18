<?php

namespace App\Modules\City\Controllers;

use Models\city as cityModel;
use Lib\core\RESTful;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use File;

class City extends RESTful {

    public function __construct() {
        $model = new cityModel;
        $controller_name = 'City';
        
        $this->table_name = 'city';
        parent::__construct($model, $controller_name);
    }
}
