<style>
    .error input {
        border: 1px solid #d62020;
    }
    .table-import input,
    .table-import select,
    .table-import textarea{
        width:180px
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
            <th>Nomor Whatsapp</th>
            <th>Tempat Lahir</th>
            <th>Tanggal Lahir</th>
            <th>Jenis Kelamin</th>
            <th>Agama</th>
            <th>Pekerjaan</th>
            <th>Alamat</th>
            <th>RT</th>
            <th>RW</th>
            @if($groups_id != 2)
                <th>Koordinator</th>
                <th>Status</th>
            @endif
        </tr>
        </thead>
        <tbody>
        <tr></tr>
        @if (count($datas) <= 0) <tr>
            <td colspan="{{ $groups_id != 2? 19 : 17 }}" style="text-align: center">Data Tidak Ditemukan</td>
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
                            <input value="{{ $district_codes[$data[4] ?? null] ?? null }}" type="hidden" id="text_district_id{{ $i }}">
                            <select name="district_id[]" data-no="{{ $i }}" class="form-select district_id {{ $errors->has('district_id')? 'is-invalid' : '' }}" id="district_id{{ $i }}">
                                <option value="">-- Pilih --</option>
                            </select>
                        </td>
                        <td nowrap>
                            <input value="{{ $subdistrict_codes[$data[5] ?? null] ?? null }}" type="hidden" id="text_subdistrict_id{{ $i }}">
                            <select name="subdistrict_id[]" data-no="{{ $i }}" class="form-select subdistrict_id {{ $errors->has('subdistrict_id')? 'is-invalid' : '' }}" id="subdistrict_id{{ $i }}">
                                <option value="">-- Pilih --</option>
                            </select>
                        </td>
                        <td nowrap>
                            <input value="{{ $data[6] ?? null }}" type="text" name="no_tps[]" class="form-control">
                        </td>
                        <td nowrap>
                            <input value="{{ $data[7] ?? null }}" type="text" name="whatsapp[]" class="form-control">
                        </td>
                        <td nowrap>
                            <input value="{{ $data[8] ?? null }}" type="text" name="pob[]" class="form-control">
                        </td>
                        <td nowrap>
                            <?php
                                $date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($data[9] ?? null);
                                $date = $date->format('Y-m-d');
                            ?>
                            <input value="{{ $date }}" type="date" name="dob[]" class="form-control">
                        </td>
                        <td nowrap>
                            <select name="gender[]" class="form-select {{ $errors->has('gender')? 'is-invalid' : '' }}">
                                <option value="L" {{ 'L' == ($data[10] ?? null)? 'selected' : '' }}>Laki - Laki</option>
                                <option value="P" {{ 'P' == ($data[10] ?? null)? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </td>
                        <td nowrap>
                            <select name="religion_id[]" class="form-select {{ $errors->has('religion_id')? 'is-invalid' : '' }}">
                                @foreach(\Models\religion::all() as $row)
                                    <option value="{{ $row->id }}" {{ $row->code == ($data[11] ?? null)? 'selected' : '' }}>{{ $row->name }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td nowrap>
                            <input value="{{ $data[12] ?? null }}" type="text" name="job_name[]" class="form-control">
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
                        @if($groups_id != 2)
                            <td nowrap>
                                <select name="coordinator_id[]" class="form-select {{ $errors->has('coordinator_id')? 'is-invalid' : '' }}">
                                    <option value="">-- Pilih --</option>
                                    @foreach(\App\Models\User::where('groups_id',2)->get() as $row)
                                        <option value="{{ $row->id }}" {{ $row->username == ($data[16] ?? null)? 'selected' : '' }}>{{ $row->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td nowrap>
                                <select name="status[]" class="form-select {{ $errors->has('status')? 'is-invalid' : '' }}">
                                    @foreach(status() as $key_status => $status_name)
                                        <option value="{{ $key_status }}" {{ $status_name == ($data[17] ?? null)? 'selected' : '' }}>{{ $status_name }}</option>
                                    @endforeach
                                </select>
                            </td>
                        @endif
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
        $('.city_id').each(function() {
            var num = $(this).data('no');
            getDistrict(num, $('#text_district_id'+num).val());
        });
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

        $('.district_id').each(function() {
            var num = $(this).data('no'),
                district_id = $('#text_district_id'+num).val();
            getSubdistrict(num, district_id, $('#text_subdistrict_id'+num).val());
        });
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