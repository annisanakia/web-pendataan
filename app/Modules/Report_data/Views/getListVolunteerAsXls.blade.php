@include('component.header_xls')
<br>
<?php $i = 0;?>
<table border="1" style="border-collapse: collapse;">
    <thead>
        <tr style="background: #e5e5e5;">
            <th width="5%" class="text-center">No</th>
            <th>Nama</th>
            <th class="text-center">Terverifikasi</th>
            <th class="text-center">Sudah Dibagikan</th>
            <th class="text-center">Total Data</th>
        </tr>
    </thead>
    <tbody>
        @php
            $i=0;
            $j=4;
            $array_excel = [];
        @endphp
        @if(count($datas) <= 0)
            <tr>
                <td colspan="5" class="text-center">Data Tidak Ditemukan</td>
            </tr>
        @else
            @foreach($datas as $data)
            <?php
                $collection_data = $collection_datas->where('volunteer_data_id',$data->id);
                $verifikasi = $collection_data->where('status',2);
                $dibagikan = $collection_data->where('status_share',2);

                $subdistricts = $collection_data->keyBy('subdistrict_id')->all();

                $j++;
                $array_excel[] = $j;
            ?>
            <tr>
                <td class="text-center">{{ ++$i }}</td>
                <td>{{ $data->name }}</td>
                <td class="text-center">{{ $verifikasi->count() }}</td>
                <td class="text-center">{{ $dibagikan->count() }}</td>
                <td class="text-center">{{ $collection_data->count() }}</td>
            </tr>
            @foreach($subdistricts as $row)
                <?php
                    $collection_data = $collection_datas->where('volunteer_data_id',$data->id)->where('subdistrict_id',$row->subdistrict_id);
                    $verifikasi = $collection_data->where('status',2);
                    $dibagikan = $collection_data->where('status_share',2);
                    $j++;
                ?>
                <tr>
                    <td class="text-center"></td>
                    <td>{{ $row->subdistrict->name ?? null }}</td>
                    <td class="text-center">{{ $verifikasi->count() }}</td>
                    <td class="text-center">{{ $dibagikan->count() }}</td>
                    <td class="text-center">{{ $collection_data->count() }}</td>
                </tr>
            @endforeach
            @endforeach
        @endif
    </tbody>
    <tfoot>
        <tr>
            <th colspan="2" class="text-center">Subtotal</th>
            <th class="text-center">=SUM({{ sprintf('C%s', implode(';C', $array_excel)) }})</th>
            <th class="text-center">=SUM({{ sprintf('D%s', implode(';D', $array_excel)) }})</th>
            <th class="text-center">=SUM({{ sprintf('E%s', implode(';E', $array_excel)) }})</th>
        </tr>
    </tfoot>
</table>