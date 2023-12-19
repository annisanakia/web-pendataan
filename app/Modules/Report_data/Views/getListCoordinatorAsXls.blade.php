@include('component.header_xls')
<br>
<?php $i = 0;?>
<table border="1" style="border-collapse: collapse;">
    <thead>
        <tr style="background: #e5e5e5;">
            <th width="5%" class="text-center">No</th>
            <th>Nama</th>
            <th class="text-center">Terverifikasi</th>
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
                <td colspan="4" class="text-center">Data Tidak Ditemukan</td>
            </tr>
        @else
            @foreach($datas as $data)
            <?php
                $collection_data = $collections_data[$data->id] ?? 0;
                $verifikasi = $collections_verif[$data->id] ?? 0;

                $collections_subdistrict = \Models\collection_data::select('subdistrict_id', \DB::raw("count(id) as total"))->where('coordinator_id',$data->id)
                        ->groupBy('subdistrict_id')->get()
                        ->pluck('total','subdistrict_id')->all();
                $collections_subdistrict_verif = \Models\collection_data::select('subdistrict_id', \DB::raw("count(id) as total"))->where('coordinator_id',$data->id)
                        ->where('status',2)->groupBy('subdistrict_id')->get()
                        ->pluck('total','subdistrict_id')->all();

                $j++;
                $array_excel[] = $j;
            ?>
            <tr>
                <td class="text-center">{{ ++$i }}</td>
                <td>{{ $data->name }}</td>
                <td class="text-center">{{ $verifikasi }}</td>
                <td class="text-center">{{ $collection_data }}</td>
            </tr>
            @foreach($data->users_subdistrict as $row)
                <?php
                    $collection_data = $collections_subdistrict[$row->subdistrict_id] ?? 0;
                    $verifikasi = $collections_subdistrict_verif[$row->subdistrict_id] ?? 0;
                    $j++;
                ?>
                <tr>
                    <td class="text-center"></td>
                    <td>{{ $row->subdistrict->name ?? null }}</td>
                    <td class="text-center">{{ $verifikasi }}</td>
                    <td class="text-center">{{ $collection_data }}</td>
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
        </tr>
    </tfoot>
</table>