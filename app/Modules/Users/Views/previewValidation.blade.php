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
    <h1 class="mt-4">Daftar Pengguna</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Daftar Pengguna</li>
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
            </ul>
        </div>

        <div class="table-responsive">
            <table class="table table-striped mt-3 table-import">
                <thead>
                <tr class="ordering">
                    <th width="10px">No</th>
                    <th>Aksi</th>
                    <th>Nama Lengkap</th>
                    <th>Grup Pengguna</th>
                    <th>Email</th>
                    <th>Nomor Telepon</th>
                    <th>Tempat Lahir</th>
                    <th>Tanggal Lahir</th>
                    <th>Alamat</th>
                    <th>Pendidikan Terakhir</th>
                    <th>Kelurahan</th>
                    <th>No TPS</th>
                    <th>Status</th>
                </tr>
                </thead>
                <tbody id="data-import">
                <tr></tr>
                @if (count($keys) <= 0)
                    <tr>
                        <td colspan="13" style="text-align: center">Data Tidak Ditemukan</td>
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
                                    <input value="{{ $name[$key] ?? null }}" type="text" name="name[{{ $key }}]" class="form-control {{ $errors->has('name.'.$key)? 'is-invalid' : '' }}">
                                    {!!$errors->first('name.'.$key, ' <span class="invalid-feedback">:message</span>')!!}
                                </td>
                                <td>
                                    <select name="groups_id[{{ $key }}]" class="form-select {{ $errors->has('groups_id.'.$key)? 'is-invalid' : '' }}">
                                        @foreach($groups as $row)
                                            <option value="{{ $row->id }}" {{ $row->id == ($groups_id[$key] ?? null)? 'selected' : '' }}>{{ $row->name }}</option>
                                        @endforeach
                                    </select>
                                    {!!$errors->first('groups_id.'.$key, ' <span class="invalid-feedback">:message</span>')!!}
                                </td>
                                <td>
                                    <input value="{{ $email[$key] ?? null }}" type="text" name="email[{{ $key }}]" class="form-control {{ $errors->has('email.'.$key)? 'is-invalid' : '' }}">
                                    {!!$errors->first('email.'.$key, ' <span class="invalid-feedback">:message</span>')!!}
                                </td>
                                <td>
                                    <input value="{{ $phone_no[$key] ?? null }}" type="text" name="phone_no[{{ $key }}]" class="form-control {{ $errors->has('phone_no.'.$key)? 'is-invalid' : '' }}">
                                    {!!$errors->first('phone_no.'.$key, ' <span class="invalid-feedback">:message</span>')!!}
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
                                    <textarea rows="3" name="address[{{ $key }}]" class="form-control {{ $errors->has('address.'.$key)? 'is-invalid' : '' }}">{{ $address[$key] ?? null }}</textarea>
                                    {!!$errors->first('address.'.$key, ' <span class="invalid-feedback">:message</span>')!!}
                                </td>
                                <td>
                                    <select name="last_education_id[{{ $key }}]" class="form-control selectpicker dropdown {{ $errors->has('last_education_id.'.$key)? 'is-invalid' : '' }}" data-size="7" data-live-search="true" data-dropup-auto="false">
                                        @foreach($last_educations as $row)
                                            <option value="{{ $row->id }}" {{ $row->id == ($last_education_id[$key] ?? null)? 'selected' : '' }}>{{ $row->name }}</option>
                                        @endforeach
                                    </select>
                                    {!!$errors->first('last_education_id.'.$key, ' <span class="invalid-feedback">:message</span>')!!}
                                </td>
                                <td>
                                    <select name="subdistrict_id[{{ $key }}]" class="form-control selectpicker dropdown {{ $errors->has('subdistrict_id.'.$key)? 'is-invalid' : '' }}" data-size="7" data-live-search="true" data-dropup-auto="false">
                                        @foreach($subdistricts as $rows)
                                            <optgroup label="{{ $rows[0]->district->name ?? 'NA' }}">
                                                @foreach($rows as $row)
                                                    <option value="{{ $row->id }}" {{ $row->id == ($subdistrict_id[$key] ?? null)? 'selected' : '' }}>{{ $row->name }}</option>
                                                @endforeach
                                            </optgroup>
                                        @endforeach
                                    </select>
                                    {!!$errors->first('subdistrict_id.'.$key, ' <span class="invalid-feedback">:message</span>')!!}
                                </td>
                                <td>
                                    <input value="{{ $no_tps[$key] ?? null }}" type="text" name="no_tps[{{ $key }}]" class="form-control {{ $errors->has('no_tps.'.$key)? 'is-invalid' : '' }}">
                                    {!!$errors->first('no_tps.'.$key, ' <span class="invalid-feedback">:message</span>')!!}
                                </td>
                                <td>
                                    <select name="status[{{ $key }}]" class="form-select {{ $errors->has('status')? 'is-invalid' : '' }}">
                                        <option value="1" {{ 1 == ($status[$key] ?? null)? 'selected' : '' }}>Aktif</option>
                                        <option value="2" {{ 2 == ($status[$key] ?? null)? 'selected' : '' }}>Tidak Aktif</option>
                                    </select>
                                </td>
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
<link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap-select/1.13.14/css/bootstrap-select.min.css')}}">
<script src="{{ asset('assets/plugins/bootstrap-select/v1.14.0-beta2/bootstrap-select.js')}}"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('.selectpicker').selectpicker('refresh');
        $('#data-import').on("click", ".remove-tr", function () {
            $(this).parents('tr').remove();
        });
    });
</script>
@endsection