<?php

namespace Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class district extends Model {

    protected $table = 'district';
    protected $guarded = ['id'];
    
    public static $rules = array(
        'code' => 'required',
        'name' => 'required',
    );
    
    public static $customMessages = array(
        'required' => 'Kolom ini wajib diisi.'
    );
    
    public function validate($data)
    {
        $v = Validator::make($data, district::$rules, district::$customMessages);
        return $v;
    }
}