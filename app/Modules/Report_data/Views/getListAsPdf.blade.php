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
            <th width="3%">No</th>
            <th width="18%">NIK</th>
            <th width="18%">Nama Lengkap</th>
            <th>Koordinator</th>
            <th width="18%">Kecamatan</th>
            <th width="18%">Kelurahan</th>
            <th width="8%">TPS</th>
            <th width="13%">TTD</th>
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
                    <td>{{ $data->name }}</td>
                    <td>{{ $data->coordinator->name ?? null }}</td>
                    <td>{{ $data->district->name ?? null }}</td>
                    <td>{{ $data->subdistrict->name ?? null }}</td>
                    <td>{{ $data->no_tps }}</td>
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