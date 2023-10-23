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
            <th width="3%">No</th>
            <th width="27%">Kecamatan</th>
            <th width="27%">Kelurahan</th>
            <th>No TPS</th>
            <th width="18%">Total Result</th>
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
                    <td>{{ $data->district->name }}</td>
                    <td>{{ $data->subdistrict->name }}</td>
                    <td>{{ $data->no_tps }}</td>
                    <td>{{ $data->total_result }}</td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>