@include('component.header_xls')
<br>
<?php $i = 0;?>
<table border="1" style="border-collapse: collapse;">
    <thead>
        <tr style="background: #e5e5e5;">
            <th width="3%">No</th>
            <th width="27%">Kecamatan</th>
            <th width="27%">Kelurahan</th>
            <th>No TPS</th>
            <th width="18%">Total Hasil</th>
        </tr>
    </thead>
    <tbody>
        @if ($datas->count() < 1)
            <tr>
                <td colspan="5" style="text-align: center">Data Tidak Ditemukan</td>
            </tr>
        @else
            @foreach ($datas as $data)
                <tr>
                    <td>{{ ++$i }}</td>
                    <td>{{ $data->district->name ?? null }}</td>
                    <td>{{ $data->subdistrict->name ?? null }}</td>
                    <td>{{ $data->no_tps }}</td>
                    <td>{{ $data->total_result }}</td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>