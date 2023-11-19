@include('component.header_pdf')

<br>
<table class="table list_content" width="100%">
    <thead>
        <tr>
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
            $total_verifikasi = 0;
            $total_dibagikan = 0;
            $total = 0;
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

                $total_verifikasi += $verifikasi->count();
                $total_dibagikan += $dibagikan->count();
                $total += $collection_data->count();

                $subdistricts = $collection_data->keyBy('subdistrict_id')->all();
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
            <th class="text-center">{{ $total_verifikasi }}</th>
            <th class="text-center">{{ $total_dibagikan }}</th>
            <th class="text-center">{{ $total }}</th>
        </tr>
    </tfoot>
</table>