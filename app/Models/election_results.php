<?php

namespace Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\Rules\File;

class election_results extends Model {

    use SoftDeletes;

    protected $table = 'election_results';
    protected $guarded = ['id'];
    
    public static $customMessages = array(
        'required' => 'Kolom ini wajib diisi.',
        'url_file.*.mimes' => 'Gambar harus bertipe JPEG, JPG, PNG',
        'url_file.*.max' => 'Ukuran file gambar harus dibawah 8 mb'
    );
    
    public function validate($data)
    {
        $rules = array(
            'no_tps' => 'required',
            'city_id' => 'required',
            'district_id' => 'required',
            'subdistrict_id' => 'required',
            'total_result' => 'required',
            'url_file.*' => [
                File::types(['jpeg', 'jpg', 'png'])
                    ->max(8192)
            ]
        );
        $v = Validator::make($data, $rules, election_results::$customMessages);
        return $v;
    }

    public function city()
    {
        return $this->belongsTo('Models\city', 'city_id', 'id');
    }

    public function district()
    {
        return $this->belongsTo('Models\district', 'district_id', 'id');
    }

    public function subdistrict()
    {
        return $this->belongsTo('Models\subdistrict', 'subdistrict_id', 'id');
    }

    public function election_results_files()
    {
        return $this->hasMany('Models\election_results_file')->orderBy('id','desc');
    }
}