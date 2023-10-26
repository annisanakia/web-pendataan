<?php

namespace Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\SoftDeletes;

class collection_data extends Model {

    use SoftDeletes;

    protected $table = 'collection_data';
    protected $guarded = ['id'];
    
    public static $customMessages = array(
        'required' => 'Kolom ini wajib diisi.',
        'numeric' => 'Isi kolom ini dengan angka.',
        'nik.unique' => 'NIK telah tersedia.',
        'nik.digits' => 'NIK harus berisikan 16 digit.',
        'whatsapp.digits_between' => 'Nomor whatsapp harus berisikan 10 sampai 12 digit.'
    );
    
    public function validate($data)
    {
        $rules = array(
            'nik' => 'required|numeric|digits:16|unique:collection_data,nik,' . ($data['id'] ?? null) . ',id,deleted_at,NULL',
            'name' => 'required',
            'city_id' => 'required',
            'district_id' => 'required',
            'subdistrict_id' => 'required',
            'no_tps' => 'required',
            'whatsapp' => 'required|numeric|digits_between:10,12',
            'rt' => 'nullable|numeric',
            'rw' => 'nullable|numeric',
        );
        $v = Validator::make($data, $rules, collection_data::$customMessages);
        return $v;
    }

    public function coordinator()
    {
        return $this->belongsTo('App\Models\User', 'coordinator_id', 'id');
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