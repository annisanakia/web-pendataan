<?php

namespace Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\SoftDeletes;

class subdistrict extends Model {

    use SoftDeletes;

    protected $table = 'subdistrict';
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
        $v = Validator::make($data, subdistrict::$rules, subdistrict::$customMessages);
        return $v;
    }

    public function district()
    {
        return $this->belongsTo('Models\district', 'district_id', 'id');
    }
}