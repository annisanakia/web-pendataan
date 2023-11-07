<?php

namespace App\Modules\Job_type\Controllers;

use Models\job_type as job_typeModel;
use Lib\core\RESTful;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use File;

class Job_type extends RESTful {

    public function __construct() {
        $model = new job_typeModel;
        $controller_name = 'Job_type';
        
        $this->table_name = 'job_type';
        parent::__construct($model, $controller_name);
    }

    public function beforeIndex($data)
    {
        $data->where('code','!=','DLL');
    }
}
