<table>
    <tr>
        <td class="red-text" colspan="10"><b><u>Mohon diperhatikan</u></b></td>
    </tr>
    <?php
        $no = 0;
        $colspan = 14;
        $groups = \Models\groups::orderBy('id','asc')->pluck('code')->all();
        $groups = sprintf('"%s"', implode('","', $groups));
        $subdistricts = \Models\subdistrict::orderBy('district_id','asc')
            ->orderBy('id','desc')
            ->get()
            ->groupBy('district.code')
            ->all();
        $last_educations = \Models\last_education::orderBy('id','asc')->pluck('code')->all();
        $last_educations = sprintf('"%s"', implode('","', $last_educations));
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
            Kolom "Grup Pengguna" diisi kode ({{ $groups }}) tanpa tanda petik
        </td>
    </tr>
    <tr>
        <td style="mso-number-format: \@;text-align:center">{{ ++$no }}.</td>
        <td colspan="{{ $colspan }}">
            Kolom "Kelurahan" diisi kode kelurahan tanpa tanda petik
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
            Kolom "Pendidikan Terakhir" diisi kode ({{ $last_educations }}) tanpa tanda petik
        </td>
    </tr>
    <tr>
        <td style="mso-number-format: \@;text-align:center">{{ ++$no }}.</td>
        <td colspan="{{ $colspan }}">
            Kolom "Status" diisi nama status ({{ sprintf('"%s"', implode('","', [1=>'Aktif',2=>'Tidak Aktif'])) }}) tanpa tanda petik
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
        </tr>
    </tbody>
</table>
