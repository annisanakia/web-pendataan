@extends('layouts.layout')

@section('content')
<style>
    .error input {
        border: 1px solid #d62020;
    }
    .table-import input,
    .table-import select,
    .table-import textarea{
        width:200px
    }
    input:read-only {
        background-color: #e9ecef !important;
    }
</style>
<div class="container-fluid px-4">
    <h1 class="mt-4">Data Pendataan</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Data Pendataan</li>
    </ol>
    <section class="section">
        <form method="post" action="{{url($controller_name.'/storeImport')}}" id="form-tab-ajax" class="form-validation">
        @csrf
        <div class="alert alert-info mt-4">
            <strong>Keterangan :</strong>
            <ul class="mb-0">
                <li>Pastikan sudah tidak ada error</li>
                <li>Jika error pastikan format benar</li>
                <li>Untuk NIK pastikan data belum ada di sistem</li>
                <li>Perhatikan kesesuaian template kode pada Wilayah, Kecamatan, Kelurahan, Jenis Kelamin, dan Agama</li>
                <li>Pastikan dan cek kembali data yang ingin diimport</li>
                <li>Cek data NIK sebelum di input ke web : <a href="https://cekdptonline.kpu.go" target="_blank">https://cekdptonline.kpu.go</a></li>
            </ul>
        </div>

        <div class="table-responsive">
            <table class="table table-striped mt-3 table-import">
                <thead>
                <tr class="ordering">
                    <th width="10px">No</th>
                    <th>Aksi</th>
                    <th>NIK</th>
                    <th>Nama Lengkap</th>
                    <th>Wilayah</th>
                    <th>Kecamatan</th>
                    <th>Kelurahan</th>
                    <th>Nomor TPS</th>
                    <th>Nomor Whatsapp</th>
                    <th>Tempat Lahir</th>
                    <th>Tanggal Lahir</th>
                    <th>Jenis Kelamin</th>
                    <th>Agama</th>
                    <th>Pekerjaan</th>
                    <th>Detail Pekerjaan</th>
                    <th>Alamat</th>
                    <th>RT</th>
                    <th>RW</th>
                    <th>Relawan Data</th>
                    <th>Status</th>
                    @if($groups_id != 2)
                        <th>Status<br>Dibagikan</th>
                        <th>Koordinator</th>
                    @endif
                </tr>
                </thead>
                <tbody id="data-import">
                <tr></tr>
                @if (count($keys) <= 0)
                    <tr>
                        <td colspan="{{ $groups_id != 2? 22 : 19 }}" style="text-align: center">Data Tidak Ditemukan</td>
                    </tr>
                @else
                    @php
                        $i = 0;
                    @endphp
                    @foreach ($keys as $key)
                        @if($key != '')
                            <tr>
                                <td>{{ ++$i }}</td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-danger px-2 py-1 remove-tr" data-id="{{ $i }}"><i class="fa-solid fa-trash"></i></button>
                                </td>
                                <td>
                                    <input value="{{ $key }}" type="hidden" name="keys[]" class="form-control">
                                    <input value="{{ $nik[$key] ?? null }}" type="text" name="nik[{{ $key }}]" class="form-control {{ $errors->has('nik.'.$key)? 'is-invalid' : '' }}">
                                    {!!$errors->first('nik.'.$key, ' <span class="invalid-feedback">:message</span>')!!}
                                </td>
                                <td>
                                    <input value="{{ $name[$key] ?? null }}" type="text" name="name[{{ $key }}]" class="form-control {{ $errors->has('name.'.$key)? 'is-invalid' : '' }}">
                                    {!!$errors->first('name.'.$key, ' <span class="invalid-feedback">:message</span>')!!}
                                </td>
                                <td>
                                    <select name="city_id[{{ $key }}]" data-no="{{ $i }}" class="form-select city_id {{ $errors->has('city_id.'.$key)? 'is-invalid' : '' }}" id="city_id{{ $i }}">
                                        @foreach(\Models\city::all() as $row)
                                            <option value="{{ $row->id }}" {{ $row->code == ($city_id[$key] ?? null)? 'selected' : '' }}>{{ $row->name }}</option>
                                        @endforeach
                                    </select>
                                    {!!$errors->first('city_id.'.$key, ' <span class="invalid-feedback">:message</span>')!!}
                                </td>
                                <td>
                                    <select name="district_id[{{ $key }}]" data-no="{{ $i }}" class="form-select district_id {{ $errors->has('district_id.'.$key)? 'is-invalid' : '' }}" id="district_id{{ $i }}">
                                        <option value="">-- Pilih --</option>
                                        @foreach($districts->where('city_id',($city_id[$key] ?? null))->all() as $row)
                                            <option value="{{ $row->id }}" {{ $row->id == ($district_id[$key] ?? null)? 'selected' : '' }}>{{ $row->name }}</option>
                                        @endforeach
                                    </select>
                                    {!!$errors->first('district_id.'.$key, ' <span class="invalid-feedback">:message</span>')!!}
                                </td>
                                <td>
                                    <select name="subdistrict_id[{{ $key }}]" data-no="{{ $i }}" class="form-select subdistrict_id {{ $errors->has('subdistrict_id.'.$key)? 'is-invalid' : '' }}" id="subdistrict_id{{ $i }}">
                                        <option value="">-- Pilih --</option>
                                        @foreach($subdistricts->where('district_id',($district_id[$key] ?? null))->all() as $row)
                                            <option value="{{ $row->id }}" {{ $row->id == ($subdistrict_id[$key] ?? null)? 'selected' : '' }}>{{ $row->name }}</option>
                                        @endforeach
                                    </select>
                                    {!!$errors->first('subdistrict_id.'.$key, ' <span class="invalid-feedback">:message</span>')!!}
                                </td>
                                <td>
                                    <input value="{{ $no_tps[$key] ?? null }}" type="text" name="no_tps[{{ $key }}]" class="form-control {{ $errors->has('no_tps.'.$key)? 'is-invalid' : '' }}">
                                    {!!$errors->first('no_tps.'.$key, ' <span class="invalid-feedback">:message</span>')!!}
                                </td>
                                <td>
                                    <input value="{{ $whatsapp[$key] ?? null }}" type="text" name="whatsapp[{{ $key }}]" class="form-control {{ $errors->has('whatsapp.'.$key)? 'is-invalid' : '' }}">
                                    {!!$errors->first('whatsapp.'.$key, ' <span class="invalid-feedback">:message</span>')!!}
                                </td>
                                <td>
                                    <input value="{{ $pob[$key] ?? null }}" type="text" name="pob[{{ $key }}]" class="form-control {{ $errors->has('pob.'.$key)? 'is-invalid' : '' }}">
                                    {!!$errors->first('pob.'.$key, ' <span class="invalid-feedback">:message</span>')!!}
                                </td>
                                <td>
                                    <input value="{{ $dob[$key] ?? null }}" type="date" name="dob[{{ $key }}]" class="form-control {{ $errors->has('dob.'.$key)? 'is-invalid' : '' }}">
                                    {!!$errors->first('dob.'.$key, ' <span class="invalid-feedback">:message</span>')!!}
                                </td>
                                <td>
                                    <select name="gender[{{ $key }}]" class="form-select {{ $errors->has('gender')? 'is-invalid' : '' }}">
                                        <option value="L" {{ 'L' == ($gender[$key] ?? null)? 'selected' : '' }}>Laki - Laki</option>
                                        <option value="P" {{ 'P' == ($gender[$key] ?? null)? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                    {!!$errors->first('gender.'.$key, ' <span class="invalid-feedback">:message</span>')!!}
                                </td>
                                <td>
                                    <select name="religion_id[{{ $key }}]" class="form-select {{ $errors->has('religion_id.'.$key)? 'is-invalid' : '' }}">
                                        @foreach(\Models\religion::all() as $row)
                                            <option value="{{ $row->id }}" {{ $row->id == ($religion_id[$key] ?? null)? 'selected' : '' }}>{{ $row->name }}</option>
                                        @endforeach
                                    </select>
                                    {!!$errors->first('religion_id.'.$key, ' <span class="invalid-feedback">:message</span>')!!}
                                </td>
                                <td>
                                    <select name="job_type_id[{{ $key }}]" data-no="{{ $i }}" class="form-select job_type_id {{ $errors->has('job_type_id.'.$key)? 'is-invalid' : '' }}" id="job_type_id{{ $i }}">
                                        <option value="">-- Pilih --</option>
                                        @foreach(\Models\job_type::orderBy(DB::raw('FIELD(code, "DLL")'))->get() as $row)
                                            <option value="{{ $row->id }}" data-code="{{ $row->code }}" {{ $row->id == ($job_type_id[$key] ?? null)? 'selected' : '' }}>{{ $row->name }}</option> 
                                        @endforeach
                                    </select>
                                    {!!$errors->first('job_type_id.'.$key, ' <span class="invalid-feedback">:message</span>')!!}
                                </td>
                                <td>
                                    <input value="{{ $job_name[$key] ?? null }}" type="text" name="job_name[{{ $key }}]" class="form-control {{ $errors->has('job_name.'.$key)? 'is-invalid' : '' }}" {!! ($job_type_id[$key] ?? null) != '1'? 'readonly' : '' !!} id="job_name{{ $i }}">
                                    {!!$errors->first('job_name.'.$key, ' <span class="invalid-feedback">:message</span>')!!}
                                </td>
                                <td>
                                    <textarea rows="3" name="address[{{ $key }}]" class="form-control {{ $errors->has('address.'.$key)? 'is-invalid' : '' }}">{{ $address[$key] ?? null }}</textarea>
                                    {!!$errors->first('address.'.$key, ' <span class="invalid-feedback">:message</span>')!!}
                                </td>
                                <td>
                                    <input value="{{ $rt[$key] ?? null }}" type="text" name="rt[{{ $key }}]" class="form-control {{ $errors->has('rt.'.$key)? 'is-invalid' : '' }}" style="width:80px">
                                    {!!$errors->first('rt.'.$key, ' <span class="invalid-feedback">:message</span>')!!}
                                </td>
                                <td>
                                    <input value="{{ $rw[$key] ?? null }}" type="text" name="rw[{{ $key }}]" class="form-control {{ $errors->has('rw.'.$key)? 'is-invalid' : '' }}" style="width:80px">
                                    {!!$errors->first('rw.'.$key, ' <span class="invalid-feedback">:message</span>')!!}
                                </td>
                                <td nowrap>
                                    <select name="volunteer_data_id[{{ $key }}]" class="form-select {{ $errors->has('volunteer_data_id.'.$key)? 'is-invalid' : '' }}">
                                        <option value="">-- Pilih --</option>
                                        @foreach(\Models\volunteer_data::all() as $row)
                                            <option value="{{ $row->id }}" {{ $row->id == ($volunteer_data_id[$key] ?? null)? 'selected' : '' }}>{{ $row->name }}</option>
                                        @endforeach
                                    </select>
                                    {!!$errors->first('volunteer_data_id.'.$key, ' <span class="invalid-feedback">:message</span>')!!}
                                </td>
                                <td nowrap>
                                    <select name="status[{{ $key }}]" class="form-select {{ $errors->has('status.'.$key)? 'is-invalid' : '' }}">
                                        @foreach(status() as $key_status => $status_name)
                                            <option value="{{ $key_status }}" {{ $key_status == ($status[$key] ?? null)? 'selected' : '' }}>{{ $status_name }}</option>
                                        @endforeach
                                    </select>
                                    {!!$errors->first('status.'.$key, ' <span class="invalid-feedback">:message</span>')!!}
                                </td>
                                @if($groups_id != 2)
                                    <td nowrap>
                                        <select name="status_share[{{ $key }}]" class="form-select {{ $errors->has('status_share.'.$key)? 'is-invalid' : '' }}">
                                            @foreach(status_share() as $key_status => $status_name)
                                                <option value="{{ $key_status }}" {{ $key_status == ($status_share[$key] ?? null)? 'selected' : '' }}>{{ $status_name }}</option>
                                            @endforeach
                                        </select>
                                        {!!$errors->first('status_share.'.$key, ' <span class="invalid-feedback">:message</span>')!!}
                                    </td>
                                    <td nowrap>
                                        <select name="coordinator_id[{{ $key }}]" class="form-select {{ $errors->has('coordinator_id.'.$key)? 'is-invalid' : '' }}">
                                            <option value="">-- Pilih --</option>
                                            @foreach(\App\Models\User::where('groups_id',2)->get() as $row)
                                                <option value="{{ $row->id }}" {{ $row->id == ($coordinator_id[$key] ?? null)? 'selected' : '' }}>{{ $row->name }}</option>
                                            @endforeach
                                        </select>
                                        {!!$errors->first('coordinator_id.'.$key, ' <span class="invalid-feedback">:message</span>')!!}
                                    </td>
                                @endif
                            </tr>
                        @endif
                    @endforeach
                @endif
                </tbody>
            </table>
        </div>
        <button type="submit" class="btn btn-success float-end mb-4">Simpan</button>
        </form>
    </section>
</div>
@endsection

@section('scripts')
<script type="text/javascript">
    $(document).ready(function () {
        $('#data-import').on("click", ".remove-tr", function () {
            $(this).parents('tr').remove();
        });
        $('.job_type_id').change(function() {
            var num = $(this).data('no'),
                option = $('option:selected', this).data('code');
            if(option == 'DLL'){
                //dll
                $('#job_name'+num).prop("readonly", false);
            }else{
                $('#job_name'+num).prop("readonly", true);
                $('#job_name'+num).val("");
            }
        });
        // $('.city_id').each(function() {
        //     var num = $(this).data('no');
        //     getDistrict(num, $('#text_district_id'+num).val());
        // });
        $('.city_id').change(function () {
            var num = $(this).data('no');
            getDistrict(num, $('#district_id'+num).val());
        });
        function getDistrict(num, id){
            var url = '{{url("reference_data/filterDistrict")}}';
            var data = {
                city_id: $('#city_id'+num).val(),
                id: id,
                blank: true
            };
            $.ajax({
                url: url,
                data: data,
                success: function(e) {
                    $('#district_id'+num).html(e);
                }
            });
        }

        // $('.district_id').each(function() {
        //     var num = $(this).data('no'),
        //         district_id = $('#text_district_id'+num).val();
        //     getSubdistrict(num, district_id, $('#text_subdistrict_id'+num).val());
        // });
        $('.district_id').change(function () {
            var num = $(this).data('no'),
                district_id = $('#district_id'+num).val();
            getSubdistrict(num, district_id, $('#subdistrict_id'+num).val());
        });
        function getSubdistrict(num, district_id, id){
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
                    $('#subdistrict_id'+num).html(e);
                }
            });
        }
    });
</script>
@endsection