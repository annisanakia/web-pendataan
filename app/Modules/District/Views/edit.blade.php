@extends('layouts.layout')

@section('content')

<div class="container-fluid px-4">
    <h1 class="mt-4">Daftar Kecamatan</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Daftar Kecamatan</li>
    </ol>
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
                                <label class="col-form-label asterisk">Name</label>
                                <input name="name" type="text" class="form-control {{ $errors->has('name')? 'is-invalid' : '' }}" value="{{ old('name') ?? $data->name }}">
                                {!!$errors->first('name', ' <span class="invalid-feedback">:message</span>')!!}
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label class="col-form-label asterisk">Kode</label>
                                <input name="code" type="text" class="form-control {{ $errors->has('code')? 'is-invalid' : '' }}" value="{{ old('code') ?? $data->code }}">
                                {!!$errors->first('code', ' <span class="invalid-feedback">:message</span>')!!}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label class="col-form-label asterisk">Wilayah</label>
                                <select name="city_id" class="form-select {{ $errors->has('city_id')? 'is-invalid' : '' }}">
                                    @foreach(\Models\city::all() as $row)
                                        <option value="{{ $row->id }}" {{ $row->id == (old('city_id') ?? $data->city_id)? 'selected' : '' }}>{{ $row->name }}</option>
                                    @endforeach
                                </select>
                                {!!$errors->first('city_id', ' <span class="invalid-feedback">:message</span>')!!}
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
</div>
@endsection