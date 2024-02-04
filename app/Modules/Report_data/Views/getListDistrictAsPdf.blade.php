<style type="text/css">
    @page {
        margin: 25px 25px 50px 25px;
    }
    h1.bigheader {
        font-size: 20px;
        margin: 0px;
    }
    h2.bigheader {
        font-size: 18px;
        margin: 0px;
    }
    .subheader {
        font-size: 14px;
        margin: 0px;
    }
    table.list_content {
        border-collapse: collapse;
        margin-top: 10px;
    }
    table.list_content td,
    table.list_content th {
        border: 1px solid black;
        padding: 2px 4px;
        vertical-align: top;
    }
    .list_content th {
        background-color: #fcfcfd;
        text-align: center;
    }
    .text-center{
        text-align:center !important
    }
    .text-end{
        text-align:right !important
    }
    .page-break{
        page-break-after:always
    }
    .table tbody tr td{
        vertical-align:middle
    }
    .pagenum:before {
        content: counter(page);
    }
    #footer {
        position: fixed;
        bottom: -30px;
        right: 0px;
        left: 0px
    }
</style>

<div id="footer">
    <table width="100%" style="border-top:1px solid">
        <tr>
            <td class="text-end">
                Hal &nbsp;&nbsp;&nbsp;: &nbsp;<span class="pagenum"></span>
            </td>
        </tr>
    </table>
</div>

<table width="100%">
    <tr>
        <td class="text-center">
            <h1 class="bigheader">{{$title_head_export}}<br>Muhammad Satrio Adinegoro</h1>
            <p class="subheader">
                {{ dateToIndo(date('Y-m-d')) }}
            </p>
        </td>
    </tr>
</table>

<br>
<table class="table list_content" width="100%">
    <thead>
        <tr>
            <th width="5%" class="text-center">No</th>
            <th>Kecamatan</th>
            <th width="28%">Kode</th>
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
                <td class="text-center">{{ (($datas->currentPage() - 1 ) * $datas->perPage() ) + ++$i }}</td>
                <td>{{ $data->district->name ?? null }}</td>
                <td>{{ $data->district->code ?? null }}</td>
                <td class="text-center">{{ $collection_data }}</td>
            </tr>
            @endforeach
        @endif
    </tbody>
    <tfoot>
        <tr>
            <th colspan="3" class="text-center">Subtotal</th>
            <th class="text-center">{{ $total }}</th>
        </tr>
    </tfoot>
</table>

<div class="page-break"></div>

<div style="background:#fff;position:absolute;bottom:-30px;height:30px;width:100%;"></div>

<br><br><br><br><br><br>
<table width="100%">
    <thead>
        <tr>
            <td class="text-center" style="font-size:27px">
                <img src="https://i.ibb.co/5vPLSRT/smartrio.png" style="width:130px"><br><br>
                {{$title_head_export}}
                <br>
                <b>Muhammad Satrio Adi Negoro</b>
            </td>
        </tr>
    </thead>
</table>