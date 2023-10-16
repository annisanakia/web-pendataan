@extends('layouts.layout')

@section('content')

<div class="pagetitle">
    <h1>Daftar Pengguna</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ url('/'.$controller_name) }}">Daftar Pengguna</a></li>
            <li class="breadcrumb-item active">Ubah Data</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-12">

        <form method="POST" action="{{ route($controller_name.'.update',$data->id) }}" class="form-validation" enctype="multipart/form-data">
            @csrf
            <div class="card">
                <div class="card-body">
                <h5 class="card-title">Ubah Data</h5>
                <div class="d-grid gap-2 d-md-block my-2 text-end">
                    @include('component.actions')
                </div>
                <!-- General Form Elements -->
                <div class="row">
                    <div class="col-sm-6">
                        <div class="mb-3">
                            <label class="col-form-label">Name</label>
                            <input name="name" type="text" class="form-control {{ $errors->has('name')? 'is-invalid' : '' }}" value="{{ old('name') ?? $data->name }}">
                            {!!$errors->first('name', ' <span class="invalid-feedback">:message</span>')!!}
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="mb-3">
                            <label class="col-form-label">Email</label>
                            <input name="email" type="text" class="form-control {{ $errors->has('email')? 'is-invalid' : '' }}" value="{{ old('email') ?? $data->email }}">
                            {!!$errors->first('email', ' <span class="invalid-feedback">:message</span>')!!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="mb-3">
                            <label class="col-form-label">Username</label>
                            <input name="username" type="text" class="form-control {{ $errors->has('name')? 'is-invalid' : '' }}" value="{{ old('username') ?? $data->username }}">
                            {!!$errors->first('username', ' <span class="invalid-feedback">:message</span>')!!}
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="mb-3">
                            <label class="col-form-label">Password</label>
                            <input name="password" type="password" class="form-control {{ $errors->has('password')? 'is-invalid' : '' }}" value="{{ old('password') }}">
                            {!!$errors->first('password', ' <span class="invalid-feedback">:message</span>')!!}
                            <span class="form-text">Abaikan jika tidak ingin mengganti password</span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="mb-3">
                            <label class="col-form-label">Grup pengguna</label>
                            <select name="groups_id" class="form-select {{ $errors->has('groups_id')? 'is-invalid' : '' }}">
                                <option value="" selected>-- Pilih --</option>
                                @foreach(\Models\groups::all() as $row)
                                    <option value="{{ $row->id }}" {{ $row->id == (old('groups_id') ?? $data->groups_id)? 'selected' : '' }}>{{ $row->name }}</option>
                                @endforeach
                            </select>
                            {!!$errors->first('groups_id', ' <span class="invalid-feedback">:message</span>')!!}
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="mb-3 {{ in_array((old('groups_id') ?? $data->groups_id),[1,2])? '' : 'd-none' }} employee_code">
                            <label class="col-form-label">Kode Pegawai</label>
                            <input name="employee_code" type="text" class="form-control {{ $errors->has('employee_code')? 'is-invalid' : '' }}" value="{{ old('employee_code') ?? $data->employee_code }}">
                            {!!$errors->first('employee_code', ' <span class="invalid-feedback">:message</span>')!!}
                        </div>
                        <div class="mb-3 {{ (old('groups_id') ?? $data->groups_id) == 3? '' : 'd-none' }} registration_code">
                            <label class="col-form-label">Kode Registrasi</label>
                            <input name="registration_code" type="text" class="form-control {{ $errors->has('registration_code')? 'is-invalid' : '' }}" value="{{ old('registration_code') ?? $data->registration_code }}">
                            {!!$errors->first('registration_code', ' <span class="invalid-feedback">:message</span>')!!}
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-6">
                        <label class="col-form-label">Foto Profil</label>
                        @if($data->url_photo != '')
                            <div class="pt-2 mb-2">
                                <a href="{{ route($controller_name.'.delete_img',$data->id) }}" class="btn btn-outline-danger btn-sm delete-img">Hapus Gambar <i class="fa-solid fa-trash"></i></a>
                            </div>
                            <div class="mb-3"><img class="object-fit-cover" style="width: 135px; height: 150px;" src="{{ asset($data->url_photo) }}"></div>
                        @endif
                        <input name="url_photo" class="form-control {{ $errors->has('url_photo')? 'is-invalid' : '' }}" type="file">
                        <div class="form-text">Upload file berformat JPEG, PNG, JPG.<br>Maksimal ukuran file 2 Mb.</div>
                        {!!$errors->first('url_photo', ' <span class="invalid-feedback">:message</span>')!!}
                    </div>
                    <div class="col-sm-6">
                        <div class="mb-3">
                            <label class="col-form-label">Nomor Telepon</label>
                            <input name="phone_no" type="text" class="form-control {{ $errors->has('phone_no')? 'is-invalid' : '' }}" value="{{ old('phone_no') ?? $data->phone_no }}">
                            {!!$errors->first('phone_no', ' <span class="invalid-feedback">:message</span>')!!}
                        </div>
                        <div class="mb-3">
                            <label class="col-form-label">Status</label>
                            <select name="status" class="form-select {{ $errors->has('status')? 'is-invalid' : '' }}">
                                <option value="" selected>-- Pilih --</option>
                                <option value="1" {{ 1 == (old('status') ?? $data->status)? 'selected' : '' }}>Aktif</option>
                                <option value="2" {{ 2 == (old('status') ?? $data->status)? 'selected' : '' }}>Tidak Aktif</option>
                            </select>
                            {!!$errors->first('status', ' <span class="invalid-feedback">:message</span>')!!}
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script type="text/javascript">
    $('select[name=groups_id]').on('change', function() {
        val = $(this).val();
        $('.employee_code').addClass('d-none');
        $('.registration_code').addClass('d-none');
        if(val == 3){
            $('.registration_code').removeClass('d-none');
        }else if(val != 3 && val != ''){
            $('.employee_code').removeClass('d-none');
        }
    });
</script>
@endsection