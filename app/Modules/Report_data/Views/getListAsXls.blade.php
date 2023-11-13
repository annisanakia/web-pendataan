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
            <th>Agama</th>
            <th>Pekerjaan</th>
            <th>Detail Pekerjaan</th>
            <th>Alamat</th>
            <th>RT</th>
            <th>RW</th>
            <th>Status</th>
            <th>Status Dibagikan</th>
            <th>Koordinator</th>
            <th>Relawan Data</th>
        </tr>
    </thead>
    <tbody>
        @if ($datas->count() < 1)
            <tr>
                <td colspan="21" style="text-align: center">Data Tidak Ditemukan</td>
            </tr>
        @else
            @foreach ($datas as $data)
                <tr>
                    <td>{{ ++$i }}</td>
                    <td style="mso-number-format: \@;">{{ $data->nik }}</td>
                    <td>{{ strtoupper($data->name) }}</td>
                    <td>{{ $data->district->name ?? null }}</td>
                    <td>{{ $data->subdistrict->name ?? null }}</td>
                    <td>{{ $data->no_tps }}</td>
                    <td>{!! $data->photo != ''? '<a href="'.$data->photo.'">'.$data->photo.'</a>' : '' !!}</td>
                    <td style="mso-number-format: \@;">{{ $data->whatsapp }}</td>
                    <td>{{ $data->dob }}</td>
                    <td>{{ $data->pob }}</td>
                    <td>{{ $data->gender == 'L'? 'Laki-laki' : 'Perempuan' }}</td>
                    <td>{{ $data->religion->name ?? null }}</td>
                    <td>{{ $data->job_type->name ?? null }}</td>
                    <td>{{ ($data->job_type->code ?? null) == 'DLL'? $data->job_name : '' }}</td>
                    <td>{{ $data->address }}</td>
                    <td>{{ $data->rt }}</td>
                    <td>{{ $data->rw }}</td>
                    <td style="{{ $data->status == 1? 'background:#dc3545;color:#fff' : '' }}">{{ status()[$data->status] ?? null }}</td>
                    <td>{{ status_share()[$data->status_share] ?? null }}</td>
                    <td>{{ $data->coordinator->name ?? null }}</td>
                    <td>{{ $data->volunteer_data->name ?? null }}</td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>