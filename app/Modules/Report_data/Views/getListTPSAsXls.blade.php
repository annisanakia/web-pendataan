@include('component.header_xls')
<br>
<?php $i = 0;?>
<table style="border-collapse: collapse;">
    <thead>
        <tr>
            <td colspan="{{ $title_col_sum }}">
                {{ $subdistrict->district->city->name ?? null }}<br>
                Kecamatan : {{ $subdistrict->district->name ?? null }}<br>
                Kelurahan : {{ $subdistrict->name ?? null }}
            </td>
        </tr>
    </thead>
</table>
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
            $j=7;
        @endphp
        @if(count($datas) <= 0)
            <tr>
                <td colspan="4" class="text-center">Data Tidak Ditemukan</td>
            </tr>
        @else
            @foreach($datas as $data)
            <?php
                $collection_data = is_numeric($data->total)? $data->total : 0;
                $verifikasi = is_numeric($data->total_verif)? $data->total_verif : 0;
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
            <th class="text-center">=SUM(C8:C{{ $j }})</th>
            <th class="text-center">=SUM(D8:D{{ $j }})</th>
        </tr>
    </tfoot>
</table>