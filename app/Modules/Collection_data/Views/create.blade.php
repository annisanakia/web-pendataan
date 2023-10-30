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

            <form method="POST" action="{{ route($controller_name.'.store') }}" class="form-validation" enctype="multipart/form-data">
                @csrf
                <div class="card">
                    <div class="card-body">
                    <h5 class="card-title">Tambah Data</h5>
                    <div class="d-grid gap-2 d-md-block my-2 text-end">
                        @include('component.actions')
                    </div>
                    <div class="alert alert-info">
                        <b>Perhatikan :</b><br>
                        <ul class="mb-0">
                            <li>Form yang bertandakan <label class="color-red">*</label> wajib diisi</li>
                            <li>Icon <i class="fa-solid fa-magnifying-glass"></i> pada form NIK berfungsi untuk mengecek data referensi</li>
                        </ul>
                    </div>
                    <div class="alert alert-danger alertNotif" id="alert_notif" style="display:none">
                        Data referensi tidak ditemukan.
                    </div>
                    <!-- General Form Elements -->
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label class="col-form-label asterisk">NIK</label>
                                <div class="input-group">
                                    <input name="nik" type="text" class="form-control {{ $errors->has('nik')? 'is-invalid' : '' }}" value="{{ old('nik') }}" id="nik">
                                    <button class="btn btn-primary" type="button" id="autocomplete"><i class="fa-solid fa-magnifying-glass"></i></button>
                                    {!!$errors->first('nik', ' <span class="invalid-feedback">:message</span>')!!}
                                </div>
                                <div class="mb-3 mt-2 f-14px text-muted">Cek data NIK sebelum di input ke web : <a href="https://cekdptonline.kpu.go.id" target="_blank">https://cekdptonline.kpu.go.id</a></div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label class="col-form-label asterisk">Nama Lengkap</label>
                                <input name="name" type="text" class="form-control {{ $errors->has('name')? 'is-invalid' : '' }}" value="{{ old('name') }}">
                                {!!$errors->first('name', ' <span class="invalid-feedback">:message</span>')!!}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label class="col-form-label asterisk">Wilayah</label>
                                <select name="city_id" class="form-select {{ $errors->has('city_id')? 'is-invalid' : '' }}" id="city_id">
                                    @foreach(\Models\city::all() as $row)
                                        <option value="{{ $row->id }}" {{ $row->id == old('city_id')? 'selected' : '' }}>{{ $row->name }}</option>
                                    @endforeach
                                </select>
                                {!!$errors->first('city_id', ' <span class="invalid-feedback">:message</span>')!!}
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label class="col-form-label asterisk">Kecamatan</label>
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
                                <label class="col-form-label asterisk">Kelurahan</label>
                                <select name="subdistrict_id" class="form-select {{ $errors->has('subdistrict_id')? 'is-invalid' : '' }}" id="subdistrict_id">
                                    <option value="">-- Pilih --</option>
                                </select>
                                {!!$errors->first('subdistrict_id', ' <span class="invalid-feedback">:message</span>')!!}
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label class="col-form-label asterisk">Nomor TPS</label>
                                <input name="no_tps" type="text" class="form-control {{ $errors->has('no_tps')? 'is-invalid' : '' }}" value="{{ old('no_tps') }}">
                                {!!$errors->first('no_tps', ' <span class="invalid-feedback">:message</span>')!!}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label class="col-form-label">Foto KTP</label>
                                <input name="photo" class="form-control {{ $errors->has('photo')? 'is-invalid' : '' }}" type="file">
                                <div class="form-text">Upload file berformat JPEG, JPG, PNG.<br>Maksimal ukuran file 2 Mb.</div>
                                {!!$errors->first('photo', ' <span class="invalid-feedback">:message</span>')!!}
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label class="col-form-label asterisk">Nomor Whatsapp</label>
                                <input name="whatsapp" type="text" class="form-control {{ $errors->has('whatsapp')? 'is-invalid' : '' }}" value="{{ old('whatsapp') }}">
                                {!!$errors->first('whatsapp', ' <span class="invalid-feedback">:message</span>')!!}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label class="col-form-label">Tempat, Tanggal Lahir</label>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <input name="pob" type="text" class="form-control {{ $errors->has('pob')? 'is-invalid' : '' }}" value="{{ old('pob') }}">
                                        {!!$errors->first('pob', ' <span class="invalid-feedback">:message</span>')!!}
                                    </div>
                                    <div class="col-sm-6">
                                        <input name="dob" type="date" class="form-control {{ $errors->has('dob')? 'is-invalid' : '' }}" value="{{ old('dob') }}">
                                        {!!$errors->first('dob', ' <span class="invalid-feedback">:message</span>')!!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <label class="col-form-label">Jenis Kelamin</label>
                            <div class="mb-3">
                                <div class="form-check d-inline-block me-2">
                                    <input class="form-check-input" type="radio" name="gender" id="gender_l" value="L" {{ old('gender') == 'L'? 'checked' : '' }}>
                                    <label class="form-check-label" for="gender_l">
                                        Laki-laki
                                    </label>
                                </div>
                                <div class="form-check d-inline-block">
                                    <input class="form-check-input" type="radio" name="gender" id="gender_p" value="P" {{ old('gender') == 'P'? 'checked' : '' }}>
                                    <label class="form-check-label" for="gender_p">
                                        Perempuan
                                    </label>
                                </div>
                                {!!$errors->first('gender', ' <span class="invalid-feedback">:message</span>')!!}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label class="col-form-label">Agama</label>
                                <select name="religion_id" class="form-select {{ $errors->has('religion_id')? 'is-invalid' : '' }}" id="religion_id">
                                    <option value="">-- Pilih --</option>
                                    @foreach(\Models\religion::all() as $row)
                                        <option value="{{ $row->id }}" {{ $row->id == old('religion_id')? 'selected' : '' }}>{{ $row->name }}</option>
                                    @endforeach
                                </select>
                                {!!$errors->first('religion_id', ' <span class="invalid-feedback">:message</span>')!!}
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label class="col-form-label">Pekerjaan</label>
                                <input name="job_name" type="text" class="form-control {{ $errors->has('job_name')? 'is-invalid' : '' }}" value="{{ old('job_name') }}">
                                {!!$errors->first('job_name', ' <span class="invalid-feedback">:message</span>')!!}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label class="col-form-label">Alamat</label>
                                <textarea class="form-control {{ $errors->has('address')? 'is-invalid' : '' }}" rows="3" name="address">{{ old('address') }}</textarea>
                                {!!$errors->first('address', ' <span class="invalid-feedback">:message</span>')!!}
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <label class="col-form-label">RT</label>
                                        <input name="rt" type="text" class="form-control {{ $errors->has('rt')? 'is-invalid' : '' }}" value="{{ old('rt') }}">
                                        {!!$errors->first('rt', ' <span class="invalid-feedback">:message</span>')!!}
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="col-form-label">RW</label>
                                        <input name="rw" type="text" class="form-control {{ $errors->has('rw')? 'is-invalid' : '' }}" value="{{ old('rw') }}">
                                        {!!$errors->first('rw', ' <span class="invalid-feedback">:message</span>')!!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                        $groups_id = Auth::user()->groups_id ?? null;
                    ?>
                    @if($groups_id == 1)
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label class="col-form-label">Koordinator</label>
                                <select name="coordinator_id" class="form-select {{ $errors->has('coordinator_id')? 'is-invalid' : '' }}">
                                    <option value="">-- Pilih --</option>
                                    @foreach(App\Models\User::where('groups_id',2)->get() as $row)
                                        <option value="{{ $row->id }}" {{ $row->id == old('coordinator_id')? 'selected' : '' }}>{{ $row->name }}</option>
                                    @endforeach
                                </select>
                                {!!$errors->first('coordinator_id', ' <span class="invalid-feedback">:message</span>')!!}
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label class="col-form-label">Status</label>
                                <select name="status" class="form-select {{ $errors->has('status')? 'is-invalid' : '' }}">
                                    @foreach(status() as $key => $status))
                                        <option value="{{ $key }}" {{ $key == old('status')? 'selected' : '' }}>{{ $status }}</option>
                                    @endforeach
                                </select>
                                {!!$errors->first('status', ' <span class="invalid-feedback">:message</span>')!!}
                            </div>
                        </div>
                    </div>
                    @endif
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
            }
        });
    }

    $('#autocomplete').click(function() {
        getAutocomplete();
    });

    function getAutocomplete(){
        var url = '{{url("collection_data/getAutocomplete")}}';
        var data = {
            nik: $('#nik').val()
        };
        $.ajax({
            url: url,
            data: data,
            success: function(e) {
                var data = JSON.parse(e);
                if(data != null){
                    $('input[name=name]').val(data.name);
                    $('input[name=city_id]').val(data.city_id);
                    getDistrict(data.city_id, data.district_id);
                    getSubdistrict(data.district_id, data.subdistrict_id);
                    $('input[name=no_tps]').val(data.no_tps);
                    $('input[name=pob]').val(data.pob);
                    $('input[name=dob]').val(data.dob);
                    if(data.gender == 'L'){
                        $('#gender_l').prop('checked',true);
                    }else if(data.gender == 'P'){v
                        $('#gender_p').prop('checked',true);
                    }
                    $('select[name=religion_id]').val(data.religion_id);
                    $('input[name=job_name]').val(data.job_name);
                    $('textarea[name=address]').val(data.address);
                    $('input[name=rt]').val(data.rt);
                    $('input[name=rw]').val(data.rw);
                }else{
                    $("#alert_notif").show();
                    setTimeout(function() { $("#alert_notif").hide(); }, 5000);
                }
            }
        });
    }
</script>
@endsection