@include('component.header_xls')
<br>
<?php $i = 0;?>
<table border="1" style="border-collapse: collapse;">
    <thead>
        <tr style="background: #e5e5e5;">
            <th>No</th>
            <th>NIK</th>
            <th>Nama Lengkap</th>
            <th>Kecamatan</th>
            <th>Kelurahan</th>
            <th>TPS</th>
            <th>Foto KTP</th>
            <th>Whatsapp</th>
            <th>Tempat Lahir</th>
            <th>Tanggal Lahir</th>
            <th>Jenis Kelamin</th>
            <th>Alamat</th>
            <th>RT</th>
            <th>RW</th>
            <th>Status</th>
            <th>Status Dibagikan</th>
            <th>Koordinator</th>
        </tr>
    </thead>
    <tbody>
        @if ($datas->count() < 1)
            <tr>
                <td colspan="17" style="text-align: center">Data Tidak Ditemukan</td>
            </tr>
        @else
            @foreach ($datas as $data)
                <tr>
                    <td>{{ ++$i }}</td>
                    <td style="mso-number-format: \@;">{{ $data->nik }}</td>
                    <td>{{ $data->name }}</td>
                    <td>{{ $data->district->name ?? null }}</td>
                    <td>{{ $data->subdistrict->name ?? null }}</td>
                    <td>{{ $data->no_tps }}</td>
                    <td>{!! $data->photo != ''? '<a href="'.$data->photo.'">'.$data->photo.'</a>' : '' !!}</td>
                    <td style="mso-number-format: \@;">{{ $data->whatsapp }}</td>
                    <td>{{ $data->dob }}</td>
                    <td>{{ $data->pob }}</td>
                    <td>{{ $data->gender == 'L'? 'Laki-laki' : 'Perempuan' }}</td>
                    <td>{{ $data->address }}</td>
                    <td>{{ $data->rt }}</td>
                    <td>{{ $data->rw }}</td>
                    <td>{{ $data->status == 2? 'Sudah diverifikasi' : '' }}</td>
                    <td>{{ $data->status_share == 2? 'Sudah dibagikan' : '' }}</td>
                    <td>{{ $data->coordinator->name ?? null }}</td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>