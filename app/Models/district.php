<?php

namespace Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\SoftDeletes;

class district extends Model {

    use SoftDeletes;

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

    public function city()
    {
        return $this->belongsTo('Models\city', 'city_id', 'id');
    }

    public function subdistrict()
    {
        return $this->hasMany('Models\subdistrict');
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

    public function election_results_data()
    {
        return $this->hasMany('Models\election_results');
    }
}