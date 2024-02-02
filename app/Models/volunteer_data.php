<?php

namespace Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\SoftDeletes;

class volunteer_data extends Model {

    use SoftDeletes;

    protected $table = 'volunteer_data';
    protected $guarded = ['id'];
    
    public static $rules = array(
        'code' => 'required',
        'name' => 'required',
        // 'coordinator_id' => 'required'
    );
    
    public static $customMessages = array(
        'required' => 'Kolom ini wajib diisi.'
    );
    
    public function validate($data)
    {
        $v = Validator::make($data, volunteer_data::$rules, volunteer_data::$customMessages);
        return $v;
    }

    public function coordinator()
    {
        return $this->belongsTo('App\Models\User', 'coordinator_id', 'id');
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