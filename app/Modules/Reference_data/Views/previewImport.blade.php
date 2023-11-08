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
            <th>NIK</th>
            <th>Nama Lengkap</th>
            <th>Wilayah</th>
            <th>Kecamatan</th>
            <th>Kelurahan</th>
            <th>Nomor TPS</th>
            <th>Tempat Lahir</th>
            <th>Tanggal Lahir</th>
            <th>Jenis Kelamin</th>
            <th>Agama</th>
            <th>Pekerjaan</th>
            <th>Detail Pekerjaan</th>
            <th>Alamat</th>
            <th>RT</th>
            <th>RW</th>
        </tr>
        </thead>
        <tbody>
        <tr></tr>
        @if (count($datas) <= 0) <tr>
            <td colspan="16" style="text-align: center">Data Tidak Ditemukan</td>
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
                            <input value="{{ $data[1] ?? null }}" type="text" name="nik[]" class="form-control">
                        </td>
                        <td nowrap>
                            <input value="{{ $data[2] ?? null }}" type="text" name="name[]" class="form-control">
                        </td>
                        <td nowrap>
                            <select name="city_id[]" data-no="{{ $i }}" class="form-select city_id {{ $errors->has('city_id')? 'is-invalid' : '' }}" id="city_id{{ $i }}">
                                @foreach(\Models\city::all() as $row)
                                    <option value="{{ $row->id }}" {{ $row->code == ($data[3] ?? null)? 'selected' : '' }}>{{ $row->name }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td nowrap>
                            <select name="district_id[]" data-no="{{ $i }}" class="form-select district_id {{ $errors->has('district_id')? 'is-invalid' : '' }}" id="district_id{{ $i }}">
                                <option value="">-- Pilih --</option>
                                @foreach($districts->where('city.code',($data[3] ?? null))->all() as $row)
                                    <option value="{{ $row->id }}" {{ $row->code == ($data[4] ?? null)? 'selected' : '' }}>{{ $row->name }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td nowrap>
                            <select name="subdistrict_id[]" data-no="{{ $i }}" class="form-select subdistrict_id {{ $errors->has('subdistrict_id')? 'is-invalid' : '' }}" id="subdistrict_id{{ $i }}">
                                <option value="">-- Pilih --</option>
                                @foreach($subdistricts->where('district.code',($data[4] ?? null))->all() as $row)
                                    <option value="{{ $row->id }}" {{ $row->code == ($data[5] ?? null)? 'selected' : '' }}>{{ $row->name }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td nowrap>
                            <input value="{{ $data[6] ?? null }}" type="text" name="no_tps[]" class="form-control">
                        </td>
                        <td nowrap>
                            <input value="{{ $data[7] ?? null }}" type="text" name="pob[]" class="form-control">
                        </td>
                        <td nowrap>
                            <?php
                                $date = $data[8] ?? null;
                                $date = $date != ''? \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($date)->format('Y-m-d') : '';
                            ?>
                            <input value="{{ $date }}" type="date" name="dob[]" class="form-control">
                        </td>
                        <td nowrap>
                            <select name="gender[]" class="form-select {{ $errors->has('gender')? 'is-invalid' : '' }}">
                                <option value="L" {{ 'L' == ($data[9] ?? null)? 'selected' : '' }}>Laki - Laki</option>
                                <option value="P" {{ 'P' == ($data[9] ?? null)? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </td>
                        <td nowrap>
                            <select name="religion_id[]" class="form-select {{ $errors->has('religion_id')? 'is-invalid' : '' }}">
                                @foreach(\Models\religion::all() as $row)
                                    <option value="{{ $row->id }}" {{ $row->code == ($data[10] ?? null)? 'selected' : '' }}>{{ $row->name }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td nowrap>
                            <select name="job_type_id[]" data-no="{{ $i }}" class="form-select job_type_id {{ $errors->has('job_type_id')? 'is-invalid' : '' }}" id="job_type_id{{ $i }}">
                                <option value="">-- Pilih --</option>
                                @foreach(\Models\job_type::orderBy(DB::raw('FIELD(code, "DLL")'))->get() as $row)
                                    <option value="{{ $row->id }}" data-code="{{ $row->code }}" {{ $row->code == ($data[11] ?? null)? 'selected' : '' }}>{{ $row->name }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td nowrap>
                            <input value="{{ ($data[11] ?? null) == 'DLL'? ($data[12] ?? null) : '' }}" type="text" name="job_name[]" class="form-control" {!! ($data[11] ?? null) != 'DLL'? 'readonly' : '' !!} id="job_name{{ $i }}">
                        </td>
                        <td nowrap>
                            <textarea rows="3" name="address[]" class="form-control">{{ $data[13] ?? null }}</textarea>
                        </td>
                        <td nowrap>
                            <input value="{{ $data[14] ?? null }}" type="text" name="rt[]" class="form-control" style="width:80px">
                        </td>
                        <td nowrap>
                            <input value="{{ $data[15] ?? null }}" type="text" name="rw[]" class="form-control" style="width:80px">
                        </td>
                    </tr>
                    <?php
                        $key++;
                    ?>
                @endif
            @endforeach
        @endif
        </tbody>
    </table>
</div>
<button type="submit" class="btn btn-success float-end mt-2">Simpan</button>
</form>

<script type="text/javascript">
    $(document).ready(function () {
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
