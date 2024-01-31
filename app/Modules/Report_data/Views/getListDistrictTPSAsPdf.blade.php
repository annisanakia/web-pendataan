@include('component.header_pdf')

<br>
<table class="table list_content" width="100%">
    <thead>
        <tr>
            <th width="5%" class="text-center" rowspan="2">No</th>
            <th rowspan="2">Kecamatan</th>
            <th rowspan="2">Jumlah Kelurahan</th>
            <th width="10%" rowspan="2">Jumlah TPS</th>
            <th class="text-center" colspan="2">Jumlah Pemilih</th>
        </tr>
        <tr>
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
                <td colspan="7" class="text-center">Data Tidak Ditemukan</td>
            </tr>
        @else
            @foreach($datas as $data)
            <?php
                $collection_data = is_numeric($data->total)? $data->total : 0;
                $verifikasi = is_numeric($data->total_verif)? $data->total_verif : 0;
                $total_subdistrict = \Models\collection_data::select('subdistrict_id')->where('district_id',$data->district_id)->groupBy('subdistrict_id')->get()->count();
                $total_tps = \Models\collection_data::select('no_tps')->where('district_id',$data->district_id)->groupBy('no_tps')->get()->count();

                $total_verifikasi += $verifikasi;
                $total += $collection_data;
            ?>
            <tr>
                <td class="text-center">{{ ++$i }}</td>
                <td>{{ $data->name ?? null }}</td>
                <td>{{ $total_subdistrict }}</td>
                <td>{{ $total_tps }}</td>
                <td class="text-center">{{ $verifikasi }}</td>
                <td class="text-center">{{ $collection_data }}</td>
            </tr>
            @endforeach
        @endif
    </tbody>
    <tfoot>
        <tr>
            <th colspan="4" class="text-center">Subtotal</th>
            <th class="text-center">{{ $total_verifikasi }}</th>
            <th class="text-center">{{ $total }}</th>
        </tr>
    </tfoot>
</table>