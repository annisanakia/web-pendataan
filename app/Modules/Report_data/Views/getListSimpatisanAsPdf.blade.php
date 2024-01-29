@include('component.header_pdf')

<style>
    @page {
        margin: 25px 25px 50px 25px;
    }
    .table tbody tr td{
        vertical-align:middle
    }
    .table.list_content tr td{
        text-align:left
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

<br>
<table width="100%">
    <thead>
        <tr>
            <td>
                Koordinator : {{ $coordinator->name ?? 'NA' }}<br>
                Jumlah simpatisan : {{ $datas->count() }} orang
            </td>
            <td width="30%" class="text-end">{{ $subdistrict->district->city->name ?? 'NA' }}<br>{{ $subdistrict->district->name ?? 'NA' }}</td>
            <td width="35%" class="text-end" style="font-size:20px">
                <b>{{ $subdistrict->name ?? 'NA' }} | TPS {{ $no_tps }}</b>
            </td>
        </tr>
    </thead>
</table>

<br>
<table class="table list_content" width="100%">
    <thead>
        <tr class="ordering">
            <th width="2%">No</th>
            <th width="13%">NIK</th>
            <th>Nama Lengkap</th>
            <th width="15%">No Telepon</th>
            <th>Kanvaser</th>
            <th width="12%">RW | RT</th>
            <th width="15%">Checklist</th>
        </tr>
    </thead>
    <tbody>
        @if ($datas->count() < 1)
            <tr>
                <td colspan="7" style="text-align: center">Data Tidak Ditemukan</td>
            </tr>
        @else
            <?php $i = 0;?>
            @foreach ($datas as $data)
                <tr>
                    <td class="text-center">{{ ++$i }}</td>
                    <td>{{ $data->nik }}</td>
                    <td>{{ strtoupper($data->name) }}</td>
                    <td>{{ $data->whatsapp ?? null }}</td>
                    <td>{{ $data->volunteer_name ?? null }}</td>
                    <td>RW {{ $data->rt ?? '-' }} | RT {{ $data->rt ?? '-' }}</td>
                    <td></td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>