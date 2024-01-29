@include('component.header_xls')

<br>
<table width="100%">
    <thead>
        <tr>
            <td colspan="8">
                {{ $subdistrict->district->city->name ?? 'NA' }}, {{ $subdistrict->district->name ?? 'NA' }}<br>
                {{ $subdistrict->name ?? 'NA' }} | TPS {{ $no_tps }}<br>
                Koordinator : {{ $coordinator->name ?? 'NA' }}<br>
                Jumlah simpatisan : {{ $datas->count() }} orang
            </td>
        </tr>
    </thead>
</table>

<br>
<?php $i = 0;?>
<table border="1" style="border-collapse: collapse;">
    <thead>
        <tr style="background: #e5e5e5;">
            <th>No</th>
            <th>NIK</th>
            <th>Nama Lengkap</th>
            <th>No Telepon</th>
            <th>Kanvaser</th>
            <th>RW</th>
            <th>RT</th>
            <th>Checklist</th>
        </tr>
    </thead>
    <tbody>
        @if ($datas->count() < 1)
            <tr>
                <td colspan="8" style="text-align: center">Data Tidak Ditemukan</td>
            </tr>
        @else
            @foreach ($datas as $data)
                <tr>
                    <td>{{ ++$i }}</td>
                    <td style="mso-number-format: \@;">{{ $data->nik }}</td>
                    <td>{{ strtoupper($data->name) }}</td>
                    <td>{{ $data->whatsapp ?? null }}</td>
                    <td>{{ $data->volunteer_name ?? null }}</td>
                    <td>{{ $data->rw ?? '-' }}</td>
                    <td>{{ $data->rt ?? '-' }}</td>
                    <td></td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>