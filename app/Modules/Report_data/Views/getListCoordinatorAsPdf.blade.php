@include('component.header_pdf')

<br>
<table class="table list_content" width="100%">
    <thead>
        <tr>
            <th width="5%" class="text-center">No</th>
            <th>Nama</th>
            <th class="text-center">Terverifikasi</th>
            <th class="text-center">Total Data</th>
        </tr>
    </thead>
    <tbody>
        @php
            $i=0;
            $total_verifikasi = 0;
            $total = 0;
        @endphp
        @if(count($datas) <= 0)
            <tr>
                <td colspan="4" class="text-center">Data Tidak Ditemukan</td>
            </tr>
        @else
            @foreach($datas as $data)
            <?php
                $collection_data = $data->collections_data;
                $verifikasi = $collections_verif[$data->id] ?? 0;

                $collections_subdistrict = \Models\collection_data::select('subdistrict_id', \DB::raw("count(id) as total"))->where('coordinator_id',$data->id)
                        ->groupBy('subdistrict_id')->get()
                        ->pluck('total','subdistrict_id')->all();
                $collections_subdistrict_verif = \Models\collection_data::select('subdistrict_id', \DB::raw("count(id) as total"))->where('coordinator_id',$data->id)
                        ->where('status',2)->groupBy('subdistrict_id')->get()
                        ->pluck('total','subdistrict_id')->all();

                $total_verifikasi += $verifikasi;
                $total += $collection_data->count();
            ?>
            <tr>
                <td class="text-center">{{ ++$i }}</td>
                <td>{{ $data->name }}</td>
                <td class="text-center">{{ $verifikasi }}</td>
                <td class="text-center">{{ $collection_data->count() }}</td>
            </tr>
            @foreach($data->users_subdistrict as $row)
                <?php
                    $collection_data = $collections_subdistrict[$row->subdistrict_id] ?? 0;
                    $verifikasi = $collections_subdistrict_verif[$row->subdistrict_id] ?? 0;
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
            <th class="text-center">{{ $total_verifikasi }}</th>
            <th class="text-center">{{ $total }}</th>
        </tr>
    </tfoot>
</table>