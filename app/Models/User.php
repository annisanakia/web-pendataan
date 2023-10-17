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
        'username',
        'password',
        'email',
        'phone_no',
        'status',
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
        'email' => 'Format alamat email salah.',
        'phone_no.numeric' => 'Nomor telepon harus berupa angka.',
        'phone_no.min_digits' => 'Masukkan nomor telepon minimal 10 angka.',
        'password.min' => 'Masukkan password minimal 6 karakter.'
    );

    public function validate($data)
    {
        $rules = array(
            'username' => 'required|unique:users,username,' . ($data['id'] ?? null) . ',id,deleted_at,NULL',
            'groups_id' => 'required',
            'name' => 'required',
            'password' => 'nullable|min:6',
            'email' => 'email|nullable',
            'phone_no' => 'numeric|nullable|min_digits:10',
            'status' => 'required'
        );
        if(!array_key_exists('id',$data)){
            $rules['password'] = 'required|min:6';
        }
        $v = Validator::make($data, $rules, user::$customMessages);
        return $v;
    }

    public function group()
    {
        return $this->belongsTo('Models\groups', 'groups_id', 'id');
    }
}
