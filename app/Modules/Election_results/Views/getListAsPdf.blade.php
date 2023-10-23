@include('component.header_pdf')

<br>
<table class="table list_content" width="100%">
    <thead>
        <tr class="ordering">
            <th width="3%">No</th>
            <th width="27%">Kecamatan</th>
            <th width="27%">Kelurahan</th>
            <th>No TPS</th>
            <th width="18%">Total Result</th>
        </tr>
    </thead>
    <tbody>
        @if ($datas->count() < 1)
            <tr>
                <td colspan="5" style="text-align: center">Data Tidak Ditemukan</td>
            </tr>
        @else
            <?php $i = 0;?>
            @foreach ($datas as $data)
                <tr>
                    <td>{{ ++$i }}</td>
                    <td>{{ $data->district->name }}</td>
                    <td>{{ $data->subdistrict->name }}</td>
                    <td>{{ $data->no_tps }}</td>
                    <td>{{ $data->total_result }}</td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>