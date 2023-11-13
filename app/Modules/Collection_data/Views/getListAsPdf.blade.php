@include('component.header_pdf')

<br>
<table class="table list_content" width="100%">
    <thead>
        <tr class="ordering">
            <th width="3%">No</th>
            <th width="18%">NIK</th>
            <th width="18%">Nama Lengkap</th>
            <th>Koordinator</th>
            <th>Relawan Data</th>
            <th width="18%">Kecamatan</th>
            <th width="18%">Kelurahan</th>
            <th width="8%">TPS</th>
        </tr>
    </thead>
    <tbody>
        @if ($datas->count() < 1)
            <tr>
                <td colspan="8" style="text-align: center">Data Tidak Ditemukan</td>
            </tr>
        @else
            <?php $i = 0;?>
            @foreach ($datas as $data)
                <tr>
                    <td>{{ ++$i }}</td>
                    <td>{{ $data->nik }}</td>
                    <td>{{ strtoupper($data->name) }}</td>
                    <td>{{ $data->coordinator->name ?? null }}</td>
                    <td>{{ $data->volunteer_data->name ?? null }}</td>
                    <td>{{ $data->district->name ?? null }}</td>
                    <td>{{ $data->subdistrict->name ?? null }}</td>
                    <td>{{ $data->no_tps }}</td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>