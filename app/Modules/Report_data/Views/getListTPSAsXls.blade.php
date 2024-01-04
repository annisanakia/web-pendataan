@include('component.header_xls')
<br>
<?php $i = 0;?>
<table border="1" style="border-collapse: collapse;">
    <thead>
        <tr style="background: #e5e5e5;">
            <th width="5%" class="text-center">No</th>
            <th>TPS</th>
            <th class="text-center">Terverifikasi</th>
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
                <td colspan="4" class="text-center">Data Tidak Ditemukan</td>
            </tr>
        @else
            @foreach($datas as $data)
            <?php
                $collection_data = $collections_data[$data->no_tps] ?? 0;
                $verifikasi = $collections_verif[$data->no_tps] ?? 0;
                $j++;
            ?>
            <tr>
                <td class="text-center">{{ ++$i }}</td>
                <td>{{ $data->no_tps }}</td>
                <td class="text-center">{{ $verifikasi }}</td>
                <td class="text-center">{{ $collection_data }}</td>
            </tr>
            @endforeach
        @endif
    </tbody>
    <tfoot>
        <tr>
            <th colspan="2" class="text-center">Subtotal</th>
            <th class="text-center">=SUM(C5:C{{ $j }})</th>
            <th class="text-center">=SUM(D5:D{{ $j }})</th>
        </tr>
    </tfoot>
</table>