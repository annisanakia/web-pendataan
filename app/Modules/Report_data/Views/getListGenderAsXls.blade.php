@include('component.header_xls')
<br>
<?php $i = 0;?>
<table border="1" style="border-collapse: collapse;">
    <thead>
        <tr style="background: #e5e5e5;">
            <th width="5%" class="text-center">No</th>
            <th>Jenis Kelamin</th>
            <th class="text-center">Terverifikasi</th>
            <th class="text-center">Sudah Dibagikan</th>
            <th class="text-center">Total Data</th>
        </tr>
    </thead>
    <tbody>
        @php
            $i=0;
        @endphp
        @if(count($datas) <= 0)
            <tr>
                <td colspan="5" class="text-center">Data Tidak Ditemukan</td>
            </tr>
        @else
            @foreach($datas as $data)
            <?php
                $collection_data = $collection_datas->where('gender',$data->gender);
                $verifikasi = $collection_data->where('status',2);
                $dibagikan = $collection_data->where('status_share',2);
                $dataBySubdistrict[] = $collection_data->count();
            ?>
            <tr>
                <td class="text-center">{{ (($datas->currentPage() - 1 ) * $datas->perPage() ) + ++$i }}</td>
                <td>{{ $data->gender == 'P'? 'Perempuan' : ($data->gender == 'L'? 'Laki-laki' : 'NA') }}</td>
                <td class="text-center">{{ $verifikasi->count() }}</td>
                <td class="text-center">{{ $dibagikan->count() }}</td>
                <td class="text-center">{{ $collection_data->count() }}</td>
            </tr>
            @endforeach
        @endif
    </tbody>
</table>