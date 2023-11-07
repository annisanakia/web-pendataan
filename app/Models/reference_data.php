<?php

namespace Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class reference_data extends Model {

    protected $table = 'reference_data';
    protected $guarded = ['id'];
    
    public static $customMessages = array(
        'required' => 'Kolom ini wajib diisi.',
        'numeric' => 'Isi kolom ini dengan angka.',
        'nik.unique' => 'NIK telah tersedia.',
        'nik.digits' => 'NIK harus berisikan 16 digit.',
        'nik.*.unique' => 'NIK telah tersedia.',
        'nik.*.digits' => 'NIK harus berisikan 16 digit.'
    );
    
    public function validate($data)
    {
        $rules = array(
            'nik' => 'required|numeric|digits:16|unique:reference_data,nik,' . ($data['id'] ?? null) . ',id,deleted_at,NULL',
            'name' => 'required',
            'city_id' => 'required',
            'district_id' => 'required',
            'subdistrict_id' => 'required',
            'no_tps' => 'nullable|numeric',
            'rt' => 'nullable|numeric',
            'rw' => 'nullable|numeric',
        );
        $v = Validator::make($data, $rules, reference_data::$customMessages);
        return $v;
    }
    
    public function validateMultiple($data)
    {
        $rules = array(
            'nik.*' => 'required|numeric|digits:16|unique:reference_data,nik,' . ($data['id'] ?? null) . ',id,deleted_at,NULL',
            'name.*' => 'required',
            'city_id.*' => 'required',
            'district_id.*' => 'required',
            'subdistrict_id.*' => 'required',
            'no_tps.*' => 'nullable|numeric',
            'rt.*' => 'nullable|numeric',
            'rw.*' => 'nullable|numeric',
        );
        $v = Validator::make($data, $rules, reference_data::$customMessages);
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

    public function job_type()
    {
        return $this->belongsTo('Models\job_type');
    }
}