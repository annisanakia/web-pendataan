<?php

namespace Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\SoftDeletes;

class log_activity extends Model {

    use SoftDeletes;

    protected $table = 'log_activity';
    protected $guarded = ['id'];
    
    public static $rules = array(
        'user_id' => 'required',
        'activity' => 'required',
    );
    
    public static $customMessages = array(
        'required' => 'Kolom ini wajib diisi.'
    );
    
    public function validate($data)
    {
        $v = Validator::make($data, log_activity::$rules, log_activity::$customMessages);
        return $v;
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }
}