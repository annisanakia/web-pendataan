<?php

namespace Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class election_results extends Model {

    protected $table = 'election_results';
    protected $guarded = ['id'];
    
    public static $customMessages = array(
        'required' => 'Kolom ini wajib diisi.',
        // 'nik.unique' => 'NIK telah tersedia.',
    );
    
    public function validate($data)
    {
        $rules = array(
            // 'nik' => 'required|unique:election_results,nik,' . ($data['id'] ?? null) . ',id,deleted_at,NULL',
            'no_tps' => 'required',
            'city_id' => 'required',
            'district_id' => 'required',
            'subdistrict_id' => 'required',
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
}