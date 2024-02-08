<style>
    .error input {
        border: 1px solid #d62020;
    }
    .table-import input,
    .table-import select,
    .table-import textarea{
        width:180px
    }
    input:read-only {
        background-color: #e9ecef !important;
    }
</style>

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
        <tbody>
        <tr></tr>
        @if (count($datas) <= 0)
            <tr>
                <td colspan="12" style="text-align: center">Data Tidak Ditemukan</td>
            </tr>
        @else
            @php
                $i = 0;
                $key = 0;
            @endphp
            @foreach ($datas as $data)
                @if($data[2] != '')
                    <tr>
                        <td>{{ ++$i }}</td>
                        <td style="background:#fb7d7f" nowrap>
                            <input value="{{ $key }}" type="hidden" name="keys[]" class="form-control">
                            <input value="{{ $data[1] ?? null }}" type="text" name="name[]" class="form-control">
                        </td>
                        <td nowrap>
                            <select name="groups_id[]" class="form-select {{ $errors->has('groups_id')? 'is-invalid' : '' }}">
                                @foreach($groups as $row)
                                    <option value="{{ $row->id }}" {{ $row->code == ($data[2] ?? null)? 'selected' : '' }}>{{ $row->name }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td nowrap>
                            <input value="{{ $data[3] ?? null }}" type="text" name="email[]" class="form-control">
                        </td>
                        <td nowrap>
                            <input value="{{ $data[4] ?? null }}" type="text" name="phone_no[]" class="form-control">
                        </td>
                        <td nowrap>
                            <input value="{{ $data[5] ?? null }}" type="text" name="pob[]" class="form-control">
                        </td>
                        <td nowrap>
                            <?php
                                $date = $data[6] ?? null;
                                $date = $date != ''? \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($date)->format('Y-m-d') : '';
                            ?>
                            <input value="{{ $date }}" type="date" name="dob[]" class="form-control">
                        </td>
                        <td nowrap>
                            <textarea rows="3" name="address[]" class="form-control">{{ $data[7] ?? null }}</textarea>
                        </td>
                        <td nowrap>
                            <select name="last_education_id[]" class="form-control selectpicker dropdown {{ $errors->has('last_education_id')? 'is-invalid' : '' }}" data-size="7" data-live-search="true" data-dropup-auto="false">
                                @foreach($last_educations as $row)
                                    <option value="{{ $row->id }}" {{ $row->code == ($data[8] ?? null)? 'selected' : '' }}>{{ $row->name }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td nowrap>
                            <select name="subdistrict_id[]" class="form-control selectpicker dropdown {{ $errors->has('subdistrict_id')? 'is-invalid' : '' }}" data-size="7" data-live-search="true" data-dropup-auto="false">
                                @foreach($subdistricts as $rows)
                                    <optgroup label="{{ $rows[0]->district->name ?? 'NA' }}">
                                        @foreach($rows as $row)
                                            <option value="{{ $row->id }}" {{ $row->code == ($data[9] ?? null)? 'selected' : '' }}>{{ $row->name }}</option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                        </td>
                        <td nowrap>
                            <?php
                                $no_tps = ltrim(($data[10] ?? null), "0");  
                            ?>
                            <input value="{{ $no_tps }}" type="text" name="no_tps[]" class="form-control">
                        </td>
                        <td nowrap>
                            <select name="status[]" class="form-select {{ $errors->has('status')? 'is-invalid' : '' }}">
                                <option value="1" {{ 'aktif' == strtolower($data[11] ?? null)? 'selected' : '' }}>Aktif</option>
                                <option value="2" {{ 'tidak aktif' == strtolower($data[11] ?? null)? 'selected' : '' }}>Tidak Aktif</option>
                            </select>
                        </td>
                    </tr>
                    <?php
                        $key++;
                    ?>
                @endif
            @endforeach
        @endif
    </table>
</div>
<button type="submit" class="btn btn-success float-end mt-2">Simpan</button>
</form>

<script type="text/javascript">
    $('.selectpicker').selectpicker('refresh');
</script>