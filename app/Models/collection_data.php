<?php

namespace Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\Rules\File;

class collection_data extends Model {

    use SoftDeletes;

    protected $table = 'collection_data';
    protected $guarded = ['id'];
    
    public static $customMessages = array(
        'required' => 'Kolom ini wajib diisi.',
        'numeric' => 'Isi kolom ini dengan angka.',
        'nik.unique' => 'NIK telah tersedia.',
        'nik.digits' => 'NIK harus berisikan 16 digit.',
        'whatsapp.digits_between' => 'Nomor whatsapp harus berisikan 10 sampai 13 digit.',
        'photo.mimes' => 'Foto KTP harus bertipe JPEG, JPG, PNG',
        'photo.max' => 'Ukuran file Foto KTP harus dibawah 2048 kilobytes',
        'nik.*.unique' => 'NIK telah tersedia.',
        'nik.*.digits' => 'NIK harus berisikan 16 digit.',
        'whatsapp.*.digits_between' => 'Nomor whatsapp harus berisikan 10 sampai 13 digit.',
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
            'whatsapp' => 'required|numeric|digits_between:10,13',
            'rt' => 'nullable|numeric',
            'rw' => 'nullable|numeric',
            'photo' => [
                File::types(['jpeg', 'jpg', 'png'])
                    ->max(2048)
            ],
        );
        // $route = \Request::route()->getName();
        // if($route != 'store'){
        //     $rules['coordinator_id'] = 'required';
        // }
        $v = Validator::make($data, $rules, collection_data::$customMessages);
        return $v;
    }
    
    public function validateMultiple($data)
    {
        $rules = array(
            'nik.*' => 'required|numeric|digits:16|unique:collection_data,nik,' . ($data['id'] ?? null) . ',id,deleted_at,NULL',
            'name.*' => 'required',
            'city_id.*' => 'required',
            'district_id.*' => 'required',
            'subdistrict_id.*' => 'required',
            'no_tps.*' => 'nullable|numeric',
            'whatsapp.*' => 'required|numeric|digits_between:10,13',
            'rt.*' => 'nullable|numeric',
            'rw.*' => 'nullable|numeric',
        );
        $v = Validator::make($data, $rules, collection_data::$customMessages);
        return $v;
    }

    public function coordinator()
    {
        return $this->belongsTo('App\Models\User', 'coordinator_id', 'id');
    }

    public function volunteer_data()
    {
        return $this->belongsTo('Models\volunteer_data');
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

    public function religion()
    {
        return $this->belongsTo('Models\religion', 'religion_id', 'id');
    }

    public function job_type()
    {
        return $this->belongsTo('Models\job_type');
    }

    public function collections_tps_data()
    {
        return $this->hasMany('Models\collection_data', 'no_tps', 'no_tps');
    }

    public function collections_tps_verif()
    {
        return $this->hasMany('Models\collection_data', 'no_tps', 'no_tps')->where('status',2);
    }

    public function collections_tps_share()
    {
        return $this->hasMany('Models\collection_data', 'no_tps', 'no_tps')->where('status_share',2);
    }
}