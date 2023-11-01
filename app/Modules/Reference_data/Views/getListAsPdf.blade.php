@include('component.header_pdf')

<br>
<table class="table list_content" width="100%">
    <thead>
        <tr class="ordering">
            <th width="3%">No</th>
            <th width="20%">NIK</th>
            <th>Nama Lengkap</th>
            <th width="20%">Kecamatan</th>
            <th width="20%">Kelurahan</th>
        </tr>
    </thead>
    <tbody>
        @if ($datas->count() < 1)
            <tr>
                <td colspan="5" style="text-align: center">Data Tidak Ditemukan</td>
            </tr>
        @else
            <?php $i = 0;?>
            @foreach ($datas as $data)
                <tr>
                    <td>{{ ++$i }}</td>
                    <td>{{ $data->nik }}</td>
                    <td>{{ strtoupper($data->name) }}</td>
                    <td>{{ $data->district->name ?? null }}</td>
                    <td>{{ $data->subdistrict->name ?? null }}</td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>