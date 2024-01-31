@include('component.header_pdf')

<br>
{{ $subdistrict->district->city->name ?? null }}<br>
Kecamatan : {{ $subdistrict->district->name ?? null }}<br>
Kelurahan : {{ $subdistrict->name ?? null }}
<table class="table list_content" width="100%">
    <thead>
        <tr>
            <th width="5%" class="text-center">No</th>
            <th>TPS</th>
            <th>RW</th>
            <th>RT</th>
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
                $collection_data = is_numeric($data->total)? $data->total : 0;
                $verifikasi = is_numeric($data->total_verif)? $data->total_verif : 0;

                $total_verifikasi += $verifikasi;
                $total += $collection_data;
            ?>
            <tr>
                <td class="text-center">{{ ++$i }}</td>
                <td>{{ $data->no_tps }}</td>
                <td>{{ $data->rw }}</td>
                <td>{{ $data->rt }}</td>
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