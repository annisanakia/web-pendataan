<?php

namespace App\Modules\District\Controllers;

use Models\district as districtModel;
use Lib\core\RESTful;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use File;

class District extends RESTful {

    public function __construct() {
        $model = new districtModel;
        $controller_name = 'District';
        
        $this->table_name = 'district';
        parent::__construct($model, $controller_name);
    }
}
