<table>
    <tr>
        <td class="red-text" colspan="10"><b><u>Mohon diperhatikan</u></b></td>
    </tr>
    <?php
        $no = 0;
        $colspan = 14;
        $citys = \Models\city::pluck('code')->all();
        $citys = sprintf('"%s"', implode('","', $citys));
        $districts = \Models\district::orderBy('id','asc')->pluck('code')->all();
        $districts = sprintf('"%s"', implode('","', $districts));
        $subdistricts = \Models\subdistrict::orderBy('district_id','asc')
            ->orderBy('id','desc')
            ->get()
            ->groupBy('district.code')
            ->all();
        $religions = \Models\religion::orderBy('id','asc')->pluck('code')->all();
        $religions = sprintf('"%s"', implode('","', $religions));
        $job_types = \Models\job_type::orderBy(DB::raw('FIELD(code, "DLL")'))->pluck('code')->all();
        $job_types = sprintf('"%s"', implode('","', $job_types));
    ?>
    <tr>
        <td width="10px" style="mso-number-format: \@;text-align:center">{{ ++$no }}.</td>
        <td colspan="{{ $colspan }}">Dilarang merubah format excel yang telah disediakan</td>
    </tr>
    <tr>
        <td style="mso-number-format: \@;text-align:center">{{ ++$no }}.</td>
        <td colspan="{{ $colspan }}">Dilarang merubah setiap judul tabel yang ada</td>
    </tr>
    <tr>
        <td style="mso-number-format: \@;text-align:center">{{ ++$no }}.</td>
        <td colspan="{{ $colspan }}">
            Pastikan simpan format excel yang telah diisi Save As sebagai Microsoft Excel 2007/2003 (.xls)
        </td>
    </tr>
    <tr>
        <td style="mso-number-format: \@;text-align:center">{{ ++$no }}.</td>
        <td colspan="{{ $colspan }}">
            Kolom "Wilayah" diisi kode wilayah ({{ $citys }}) tanpa tanda petik
        </td>
    </tr>
    <tr>
        <td style="mso-number-format: \@;text-align:center">{{ ++$no }}.</td>
        <td colspan="{{ $colspan }}">
            Kolom "Kecamatan" diisi kode kecamatan ({{ $districts }}) tanpa tanda petik
        </td>
    </tr>
    <tr>
        <td style="mso-number-format: \@;text-align:center">{{ ++$no }}.</td>
        <td colspan="{{ $colspan }}">
            Kolom "Kelurahan" diisi kode kelurahan berdasarkan kecamatan tanpa tanda petik
        </td>
    </tr>
    <tr>
        <td></td>
        <td colspan="{{ $colspan }}">
            @foreach($subdistricts as $district_code => $subdistrict)
                "{{ $district_code }}" :
                {{ sprintf('"%s"', implode('","', $subdistrict->pluck('code')->all())) }}<br>
            @endforeach
        </td>
    </tr>
    <tr>
        <td style="mso-number-format: \@;text-align:center">{{ ++$no }}.</td>
        <td colspan="{{ $colspan }}">
            Kolom "Jenis Kelamin" diisi kode ("L"/"P") tanpa tanda petik
        </td>
    </tr>
    <tr>
        <td style="mso-number-format: \@;text-align:center">{{ ++$no }}.</td>
        <td colspan="{{ $colspan }}">
            Kolom "Agama" diisi kode ({{ $religions }}) tanpa tanda petik
        </td>
    </tr>
    <tr>
        <td style="mso-number-format: \@;text-align:center">{{ ++$no }}.</td>
        <td colspan="{{ $colspan }}">
            Kolom "Pekerjaan" diisi kode ({{ $job_types }}) tanpa tanda petik
        </td>
    </tr>
    <tr>
        <td style="mso-number-format: \@;text-align:center">{{ ++$no }}.</td>
        <td colspan="{{ $colspan }}">
            Kolom "Detail Pekerjaan" diisi jika kolom "Pekerjaan" diisi dengan "DLL"
        </td>
    </tr>
    <tr>
        <td colspan="{{ $colspan }}"></td>
    </tr>
</table>
<table border="1" style="border-collapse: collapse;">
    <thead>
        <tr style="background: #e5e5e5;">
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
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
    </tbody>
</table>
