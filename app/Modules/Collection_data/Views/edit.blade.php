@extends('layouts.layout')

@section('content')

<div class="container-fluid px-4">
    <h1 class="mt-4">Data Pendataan</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Data Pendataan</li>
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
                                <label class="col-form-label">NIK</label>
                                <input name="nik" type="text" class="form-control {{ $errors->has('nik')? 'is-invalid' : '' }}" value="{{ old('nik') ?? $data->nik }}">
                                {!!$errors->first('nik', ' <span class="invalid-feedback">:message</span>')!!}
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label class="col-form-label">Name</label>
                                <input name="name" type="text" class="form-control {{ $errors->has('name')? 'is-invalid' : '' }}" value="{{ old('name') ?? $data->name }}">
                                {!!$errors->first('name', ' <span class="invalid-feedback">:message</span>')!!}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label class="col-form-label">Wilayah</label>
                                <select name="city_id" class="form-select {{ $errors->has('city_id')? 'is-invalid' : '' }}">
                                    @foreach(\Models\city::all() as $row)
                                        <option value="{{ $row->id }}" {{ $row->id == (old('city_id') ?? $data->city_id)? 'selected' : '' }}>{{ $row->name }}</option>
                                    @endforeach
                                </select>
                                {!!$errors->first('city_id', ' <span class="invalid-feedback">:message</span>')!!}
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label class="col-form-label">Kecamatan</label>
                                <select name="district_id" class="form-select {{ $errors->has('district_id')? 'is-invalid' : '' }}" id="district_id">
                                    <option value="">-- Pilih --</option>
                                </select>
                                {!!$errors->first('district_id', ' <span class="invalid-feedback">:message</span>')!!}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label class="col-form-label">Kelurahan</label>
                                <select name="subdistrict_id" class="form-select {{ $errors->has('subdistrict_id')? 'is-invalid' : '' }}" id="subdistrict_id">
                                    <option value="">-- Pilih --</option>
                                </select>
                                {!!$errors->first('subdistrict_id', ' <span class="invalid-feedback">:message</span>')!!}
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label class="col-form-label">Nomor TPS</label>
                                <input name="no_tps" type="text" class="form-control {{ $errors->has('no_tps')? 'is-invalid' : '' }}" value="{{ old('no_tps') ?? $data->no_tps }}">
                                {!!$errors->first('no_tps', ' <span class="invalid-feedback">:message</span>')!!}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <label class="col-form-label">Foto KTP</label>
                            @if($data->photo != '')
                                <div class="pt-2 mb-2">
                                    <a href="{{ route($controller_name.'.delete_img',$data->id) }}" class="btn btn-outline-danger btn-sm delete-img">Hapus Gambar <i class="fa-solid fa-trash"></i></a>
                                </div>
                                <div class="mb-3"><img class="object-fit-cover" style="height: 150px;" src="{{ asset($data->photo) }}"></div>
                            @endif
                            <input name="photo" class="form-control {{ $errors->has('photo')? 'is-invalid' : '' }}" type="file">
                            <div class="form-text">Upload file berformat JPEG, PNG, JPG.<br>Maksimal ukuran file 2 Mb.</div>
                            {!!$errors->first('photo', ' <span class="invalid-feedback">:message</span>')!!}
                        </div>
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label class="col-form-label">Nomor Whatsapp</label>
                                <input name="whatsapp" type="text" class="form-control {{ $errors->has('whatsapp')? 'is-invalid' : '' }}" value="{{ old('whatsapp') ?? $data->whatsapp }}">
                                {!!$errors->first('whatsapp', ' <span class="invalid-feedback">:message</span>')!!}
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
<script type="text/javascript">
    $('#city_id').change(function() {
        getDistrict($(this).val());
    });

    $('#district_id').change(function() {
        getSubdistrict($(this).val());
    });

    getDistrict("{{ old('city_id') ?? ($data->city_id ?? 1) }}", "{{ old('district_id') ?? ($data->district_id ?? null) }}");
    function getDistrict(city_id, id){
        var url = '{{url("reference_data/filterDistrict")}}';
        var data = {
            city_id: city_id,
            id: id,
            blank: true
        };
        $.ajax({
            url: url,
            data: data,
            success: function(e) {
                $('#district_id').html(e);
                $('.selectpicker').selectpicker('refresh');
            }
        });
    }

    getSubdistrict("{{ old('district_id') ?? ($data->district_id ?? null) }}", "{{ old('subdistrict_id') ?? ($data->subdistrict_id ?? null) }}");
    function getSubdistrict(district_id, id){
        var url = '{{url("reference_data/filterSubdistrict")}}';
        var data = {
            district_id: district_id,
            id: id,
            blank: true
        };
        $.ajax({
            url: url,
            data: data,
            success: function(e) {
                $('#subdistrict_id').html(e);
                $('.selectpicker').selectpicker('refresh');
            }
        });
    }
</script>
@endsection