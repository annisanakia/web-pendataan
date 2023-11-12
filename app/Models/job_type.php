<?php

namespace Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\SoftDeletes;

class job_type extends Model {

    use SoftDeletes;

    protected $table = 'job_type';
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
        $v = Validator::make($data, job_type::$rules, job_type::$customMessages);
        return $v;
    }

    public function collections_data()
    {
        return $this->hasMany('Models\collection_data');
    }

    public function collections_verif()
    {
        return $this->hasMany('Models\collection_data')->where('status',2);
    }

    public function collections_share()
    {
        return $this->hasMany('Models\collection_data')->where('status_share',2);
    }
}