@include('component.header_xls')
<style>
    .table {
        border-collapse: collapse;
        width: 100%;
    }

    .table th,
    .table td {
        border: 1px solid #000;
        padding: 4px;
        white-space: nowrap;
    }

    .table thead tr {
        background: #fcfcfd;
    }

    .bigheader {
        font-size: 19px;
        font-weight: bold;
        color: black;
        margin: 0px;
    }

    .subheader {
        font-size: 16px;
        margin: 0px;
    }

    .text-center {
        text-align: center;
    }
</style>

<br>
<?php $i = 0;?>
<table class="table">
    <thead>
        <tr>
            <th>No</th>
            <th>NIK</th>
            <th>Name</th>
            <th>Kecamatan</th>
            <th>Kelurahan</th>
        </tr>
    </thead>
    <tbody>
        @if ($datas->count() < 1)
            <tr>
                <td colspan="5" style="text-align: center">Data Tidak Ditemukan</td>
            </tr>
        @else
            @foreach ($datas as $data)
                <tr>
                    <td>{{ ++$i }}</td>
                    <td>{{ $data->nik }}</td>
                    <td>{{ $data->name }}</td>
                    <td>{{ $data->district->name }}</td>
                    <td>{{ $data->subdistrict->name }}</td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>