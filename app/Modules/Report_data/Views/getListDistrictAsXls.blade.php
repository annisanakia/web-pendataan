@include('component.header_xls')
<br>
<?php $i = 0;?>
<table border="1" style="border-collapse: collapse;">
    <thead>
        <tr style="background: #e5e5e5;">
            <th width="5%" class="text-center">No</th>
            <th>Kecamatan</th>
            <th width="28%">Kode</th>
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
                <td colspan="5" class="text-center">Data Tidak Ditemukan</td>
            </tr>
        @else
            @foreach($datas as $data)
            <?php
                $collection_data = $collection_datas->where('district_id',$data->id);
                $verifikasi = $collection_data->where('status',2);
                $dibagikan = $collection_data->where('status_share',2);
                $j++;
            ?>
            <tr>
                <td class="text-center">{{ ++$i }}</td>
                <td>{{ $data->name }}</td>
                <td>{{ $data->code }}</td>
                <td class="text-center">{{ $verifikasi->count() }}</td>
                <td class="text-center">{{ $collection_data->count() }}</td>
            </tr>
            @endforeach
        @endif
    </tbody>
    <tfoot>
        <tr>
            <th colspan="3" class="text-center">Subtotal</th>
            <th class="text-center">=SUM(D5:D{{ $j }})</th>
            <th class="text-center">=SUM(E5:E{{ $j }})</th>
        </tr>
    </tfoot>
</table>