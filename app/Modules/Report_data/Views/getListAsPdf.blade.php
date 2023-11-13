@include('component.header_pdf')

<style>
    .table tbody tr td{
        vertical-align:middle
    }
</style>

<br>
<table class="table list_content" width="100%">
    <thead>
        <tr class="ordering">
            <th width="2%">No</th>
            <th width="12%">NIK</th>
            <th width="18%">Nama Lengkap</th>
            <th>Koordinator</th>
            <th>Relawan Data</th>
            <th width="12%">Kecamatan</th>
            <th width="12%">Kelurahan</th>
            <th width="5%">TPS</th>
            <th width="10%">Status</th>
            <th width="13%">TTD</th>
        </tr>
    </thead>
    <tbody>
        @if ($datas->count() < 1)
            <tr>
                <td colspan="10" style="text-align: center">Data Tidak Ditemukan</td>
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
                    <td nowrap>{{ status()[$data->status] ?? null }}</td>
                    <td style="height:40px"></td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>

<br>
<table width="100%">
    <tr>
        <td style="width:80%"></td>
        <td style="border-bottom:1px solid;text-align:center">
            Tanda Tangan Koordinator
            <br><br><br><br><br><br>
        </td>
        <td style="width:5%"></td>
    </tr>
</table>