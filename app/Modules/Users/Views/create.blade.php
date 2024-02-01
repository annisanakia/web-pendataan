@extends('layouts.layout')

@section('content')

<div class="container-fluid px-4">
    <h1 class="mt-4">Daftar Pengguna</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Daftar Pengguna</li>
    </ol>
    <section class="section">
        <div class="row">
            <div class="col-lg-12">

            <form method="POST" action="{{ route($controller_name.'.store') }}" class="form-validation" enctype="multipart/form-data">
                @csrf
                <div class="card">
                    <div class="card-body">
                    <h5 class="card-title">Tambah Data</h5>
                    <div class="d-grid gap-2 d-md-block my-2 text-end">
                        @include('component.actions')
                    </div>
                    <!-- General Form Elements -->
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label class="col-form-label asterisk">Name</label>
                                <input name="name" type="text" class="form-control {{ $errors->has('name')? 'is-invalid' : '' }}" value="{{ old('name') }}">
                                {!!$errors->first('name', ' <span class="invalid-feedback">:message</span>')!!}
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label class="col-form-label asterisk">Grup pengguna</label>
                                <select name="groups_id" class="form-select {{ $errors->has('groups_id')? 'is-invalid' : '' }}">
                                    <option value="" selected>-- Pilih --</option>
                                    @foreach(\Models\groups::all() as $row)
                                        <option value="{{ $row->id }}" {{ $row->id == old('groups_id')? 'selected' : '' }}>{{ $row->name }}</option>
                                    @endforeach
                                </select>
                                {!!$errors->first('groups_id', ' <span class="invalid-feedback">:message</span>')!!}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label class="col-form-label asterisk">Username</label>
                                <input name="username" type="text" class="form-control {{ $errors->has('username')? 'is-invalid' : '' }}" value="{{ old('username') }}">
                                {!!$errors->first('username', ' <span class="invalid-feedback">:message</span>')!!}
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label class="col-form-label asterisk">Password</label>
                                <input name="password" type="password" class="form-control {{ $errors->has('password')? 'is-invalid' : '' }}" value="{{ old('password') }}">
                                {!!$errors->first('password', ' <span class="invalid-feedback">:message</span>')!!}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label class="col-form-label">Email</label>
                                <input name="email" type="text" class="form-control {{ $errors->has('email')? 'is-invalid' : '' }}" value="{{ old('email') }}">
                                {!!$errors->first('email', ' <span class="invalid-feedback">:message</span>')!!}
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label class="col-form-label">Nomor Telepon</label>
                                <input name="phone_no" type="text" class="form-control {{ $errors->has('phone_no')? 'is-invalid' : '' }}" value="{{ old('phone_no') }}">
                                {!!$errors->first('phone_no', ' <span class="invalid-feedback">:message</span>')!!}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label class="col-form-label asterisk">Status</label>
                                <select name="status" class="form-select {{ $errors->has('status')? 'is-invalid' : '' }}">
                                    <option value="" selected>-- Pilih --</option>
                                    <option value="1" {{ 1 == (old('status') ?? 1)? 'selected' : '' }}>Aktif</option>
                                    <option value="2" {{ 2 == (old('status') ?? 1)? 'selected' : '' }}>Tidak Aktif</option>
                                </select>
                                {!!$errors->first('status', ' <span class="invalid-feedback">:message</span>')!!}
                            </div>
                        </div>
                        <div class="col-sm-6 hidden" id="coordinator_subdistrict">
                            <div class="mb-3">
                                <label class="col-form-label asterisk">Kelurahan</label>
                                <?php
                                    $subdistrict_ids = is_array(old('subdistrict_ids'))? old('subdistrict_ids') : [];
                                ?>
                                <select name="subdistrict_ids[]" class="form-control selectpicker {{ $errors->has('subdistrict_ids')? 'is-invalid' : '' }}" data-size="7" data-live-search="true" data-actions-box="true" data-selected-text-format="count" title="-- Pilih --" multiple>
                                    @foreach(\Models\subdistrict::all() as $row)
                                        <option value="{{ $row->id }}" {{ in_array($row->id,$subdistrict_ids)? 'selected' : '' }}>{{ $row->name }}</option>
                                    @endforeach
                                </select>
                                {!!$errors->first('subdistrict_ids', ' <span class="invalid-feedback">:message</span>')!!}
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
</div>
@endsection

@section('scripts')
<link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap-select/1.13.14/css/bootstrap-select.min.css')}}">
<script src="{{ asset('assets/plugins/bootstrap-select/v1.14.0-beta2/bootstrap-select.js')}}"></script>
<script type="text/javascript">
    $('.selectpicker').selectpicker('refresh');
    $('select[name=groups_id]').on('change', function() {
        val = $(this).val();
        getSubdistrict(val);
    });
    getSubdistrict("{{ old('groups_id') ?? ($data->groups_id ?? null) }}", "{{ old('groups_id') ?? ($data->groups_id ?? null) }}");
    function getSubdistrict(val){
        $('#coordinator_subdistrict').addClass('d-none');
        if(val == 2 || val == 3){
            $('#coordinator_subdistrict').removeClass('d-none');
        }
    }
</script>
@endsection