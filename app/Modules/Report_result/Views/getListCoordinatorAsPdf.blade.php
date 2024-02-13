@include('component.header_pdf')

<br>
<table class="table list_content" width="100%">
    <thead>
        <tr>
            <th width="5%" class="text-center">No</th>
            <th>Nama Koordinator</th>
            <th>Kecamatan</th>
            <th>Kelurahan</th>
            <th>TPS</th>
            <th class="text-center">Total Data</th>
        </tr>
    </thead>
    <tbody>
        @php
            $i=0;
            $total = 0;
        @endphp
        @if(count($datas) <= 0)
            <tr>
                <td colspan="6" class="text-center">Data Tidak Ditemukan</td>
            </tr>
        @else
            @foreach($datas as $data)
            <?php
                $total_data = $data->total_result;
                $total += $total_data;
            ?>
            <tr>
                <td class="text-center">{{ ++$i }}</td>
                <td>{{ $data->user_name }}</td>
                <td>{{ $data->district_name }}</td>
                <td>{{ $data->subdistrict_name }}</td>
                <td>{{ $data->no_tps }}</td>
                <td class="text-center">{{ $total_data }}</td>
            </tr>
            @endforeach
        @endif
    </tbody>
    <tfoot>
        <tr>
            <th colspan="5" class="text-end">Subtotal</th>
            <th class="text-center">{{ $total }}</th>
        </tr>
    </tfoot>
</table>