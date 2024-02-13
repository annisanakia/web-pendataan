@include('component.header_xls')
<br>
<?php $i = 0;?>
<table border="1" style="border-collapse: collapse;">
    <thead>
        <tr style="background: #e5e5e5;">
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
            $j=4;
        @endphp
        @if(count($datas) <= 0)
            <tr>
                <td colspan="6" class="text-center">Data Tidak Ditemukan</td>
            </tr>
        @else
            @foreach($datas as $data)
            <?php
                $total_data = $data->total_result;
                $j++;
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
            <th class="text-center">=SUM(F5:F{{ $j }})</th>
        </tr>
    </tfoot>
</table>