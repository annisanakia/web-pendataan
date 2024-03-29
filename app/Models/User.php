<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'groups_id',
        'name',
        'code',
        'username',
        'password',
        'email',
        'phone_no',
        'status',
        'subdistrict_id',
        'last_education_id',
        'pob',
        'dob',
        'address',
        'no_tps',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    protected $dates = ['deleted_at'];

    public static $customMessages = array(
        'required' => 'Kolom ini wajib diisi.',
        'username.unique' => 'Nama pengguna telah tersedia.',
        'email.email' => 'Format alamat email salah.',
        'email.unique' => 'Email telah tersedia.',
        'phone_no.numeric' => 'Nomor telepon harus berupa angka.',
        'phone_no.digits_between' => 'Nomor telepon harus berisikan 10 sampai 13 digit.',
        'password.min' => 'Masukkan password minimal 6 karakter.',

        'email.*.email' => 'Format alamat email salah.',
        'email.*.unique' => 'Email telah tersedia.',
        'phone_no.*.numeric' => 'Nomor telepon harus berupa angka.',
        'phone_no.*.digits_between' => 'Nomor harus berisikan 10 sampai 13 digit.',
    );

    public function validate($data)
    {
        $rules = array(
            'username' => 'required|unique:users,username,' . ($data['id'] ?? null) . ',id,deleted_at,NULL',
            'groups_id' => 'required',
            'name' => 'required',
            'password' => 'nullable|min:6',
            'email' => 'nullable|email|unique:users,email,' . ($data['id'] ?? null) . ',id,deleted_at,NULL',
            'phone_no' => 'numeric|nullable|digits_between:10,13',
            'status' => 'required'
        );
        if($data['groups_id'] == 2){
            $rules['subdistrict_ids'] = 'required|array';
        }
        if($data['groups_id'] == 3){
            $rules['subdistrict_id'] = 'required';
            $rules['no_tps'] = 'required';
        }
        if(!array_key_exists('id',$data)){
            $rules['password'] = 'required|min:6';
        }
        $v = Validator::make($data, $rules, user::$customMessages);
        return $v;
    }
    
    public function validateMultiple($data)
    {
        $rules = array(
            'name.*' => 'required',
            'groups_id.*' => 'required',
            'email.*' => 'nullable|email|unique:users,email,null,id,deleted_at,NULL',
            'phone_no.*' => 'numeric|nullable|digits_between:10,13',
            'status.*' => 'required',
            'subdistrict_id.*' => 'required',
            'no_tps.*' => 'required',
            'dob.*' => 'required'
        );
        $v = Validator::make($data, $rules, user::$customMessages);
        return $v;
    }

    public function group()
    {
        return $this->belongsTo('Models\groups', 'groups_id', 'id');
    }

    public function subdistrict()
    {
        return $this->belongsTo('Models\subdistrict', 'subdistrict_id', 'id');
    }

    public function users_subdistrict()
    {
        return $this->hasMany('Models\users_subdistrict', 'user_id', 'id');
    }

    public function collections_data()
    {
        return $this->hasMany('Models\collection_data', 'coordinator_id', 'id');
    }

    public function collections_verif()
    {
        return $this->hasMany('Models\collection_data', 'coordinator_id', 'id')->where('status',2);
    }

    public function collections_share()
    {
        return $this->hasMany('Models\collection_data', 'coordinator_id', 'id')->where('status_share',2);
    }
}
