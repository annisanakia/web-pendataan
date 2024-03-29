@include('component.header_pdf')

<style>
    table.list_content {
        font-size: 13px;
    }
</style>

<br>
<table class="table list_content" width="100%">
    <thead>
        <tr class="ordering">
            <th width="10px">No</th>
            <th>NIK</th>
            <th>No Whatsapp</th>
            <th>Nama Lengkap</th>
            <th>Koordinator</th>
            <th>Relawan Data</th>
            <th>Kecamatan</th>
            <th>Kelurahan</th>
            <th>TPS</th>
            <th>Alamat</th>
            <th>RT</th>
            <th>RW</th>
        </tr>
    </thead>
    <tbody>
        @if ($datas->count() < 1)
            <tr>
                <td colspan="12" style="text-align: center">Data Tidak Ditemukan</td>
            </tr>
        @else
            <?php $i = 0;?>
            @foreach ($datas as $data)
                <tr>
                    <td>{{ ++$i }}</td>
                    <td>{{ $data->nik }}</td>
                    <td>{{ $data->whatsapp }}</td>
                    <td>{{ strtoupper($data->name) }}</td>
                    <td>{{ $data->coordinator->name ?? null }}</td>
                    <td>{{ $data->volunteer_data->name ?? null }}</td>
                    <td>{{ $data->district->name ?? null }}</td>
                    <td>{{ $data->subdistrict->name ?? null }}</td>
                    <td>{{ $data->no_tps }}</td>
                    <td>{{ $data->address }}</td>
                    <td>{{ $data->rt }}</td>
                    <td>{{ $data->rw }}</td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>