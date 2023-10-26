<?php

namespace Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\SoftDeletes;

class users_subdistrict extends Model {

    use SoftDeletes;

    protected $table = 'users_subdistrict';
    protected $guarded = ['id'];
    
    public static $rules = array(
        'user_id' => 'required',
        'subdistrict_id' => 'required',
    );
    
    public static $customMessages = array(
        'required' => 'Kolom ini wajib diisi.'
    );
    
    public function validate($data)
    {
        $v = Validator::make($data, users_subdistrict::$rules, users_subdistrict::$customMessages);
        return $v;
    }
}