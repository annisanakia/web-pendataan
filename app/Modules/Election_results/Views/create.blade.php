@extends('layouts.layout')

@section('content')

<div class="container-fluid px-4">
    <h1 class="mt-4">Data Hasil Pemilu</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Data Hasil Pemilu</li>
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
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label class="col-form-label asterisk">Nomor TPS</label>
                                <input name="no_tps" type="text" class="form-control {{ $errors->has('no_tps')? 'is-invalid' : '' }}" value="{{ old('no_tps') }}">
                                {!!$errors->first('no_tps', ' <span class="invalid-feedback">:message</span>')!!}
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label class="col-form-label">Total Hasil</label>
                                <input name="total_result" type="text" class="form-control {{ $errors->has('total_result')? 'is-invalid' : '' }}" value="{{ old('total_result') }}">
                                {!!$errors->first('total_result', ' <span class="invalid-feedback">:message</span>')!!}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <label class="col-form-label">Upload bukti gambar</label>
                            <div class="row">
                                <div class="col-md-12">
                                    <button class="btn btn-primary add-data mb-3" type="button">
                                        Tambah Gambar <i class="fa-solid fa-plus px-2"></i>
                                    </button>
                                </div>
                                <div class="col-md-6">
                                    <table class="w-100">
                                        <tbody id="body-data">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
</div>
@endsection

<table class="d-none">
    <tbody id="template-data">
        <tr>
            <td class="pb-2">
                <input type="file" class="form-control text-center form-file">
            </td>
            <td class="action text-center" nowrap>
                <button class="btn btn-outline-danger px-1 py-2 remove-data" type="button">
                    <i class="fa-solid fa-minus px-2"></i>
                </button>
            </td>
        </tr>
    </tbody>
</table>

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

    var maxGroup = 10;
    $(".add-data").click(function(){
        if($('#body-data').find('tr').length < maxGroup){
            $('#template-data tr').clone(false).appendTo('#body-data');
            setNameInput();
        }else{
            swalSaveButtons.fire('Perhatian!', 'Maksimal opsi jawaban sebanyak '+maxGroup+' opsi.', 'warning')
        }
    });
    
    $('#body-data').on("click", ".remove-data", function () {
        $(this).parents('tr').remove();
        setNameInput();
    });

    function setNameInput(){
        var i = 0;
        $('#body-data tr').each(function() {
            $(this).find('.form-file').attr('name', 'url_file['+i+']');
            i++;
        });
    }
</script>
@endsection