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
            <h1 class="bigheader">{{$title_head_export}}</h1>
            <p class="subheader">
                {{ dateToIndo(date('Y-m-d')) }}
            </p>
        </td>
    </tr>
</table>

<br>
<table width="100%">
    <thead>
        <tr>
            <td>
                @if(isset($coordinator_tps->name))
                    Koordinator TPS : {{ $coordinator_tps->name ?? 'NA' }}<br>
                @endif
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
            <th width="12%">No Telepon</th>
            @if(!isset($coordinator->name))
                <th>Kode</th>
            @endif
            <th>Kanvaser</th>
            <th width="12%">RW | RT</th>
            <th width="15%">Checklist</th>
        </tr>
    </thead>
    <tbody>
        <?php
            $groupBykanvaser = [];
            $coordinators = [];
        ?>
        @if ($datas->count() < 1)
            <tr>
                <td colspan="{{ isset($coordinator->name)? 7 : 8 }}" style="text-align: center">Data Tidak Ditemukan</td>
            </tr>
        @else
            <?php $i = 0;?>
            @foreach ($datas as $data)
                <?php
                    $groupBykanvaser[$data->volunteer_name][($data->rw ?? '-').'#'.($data->rt ?? '-')][] =  1;
                    $coordinators[$data->coordinator_name][] = 1;
                ?>
                <tr>
                    <td class="text-center">{{ ++$i }}</td>
                    <td>{{ $data->nik }}</td>
                    <td>{{ strtoupper($data->name) }}</td>
                    <td>{{ $data->whatsapp ?? null }}</td>
                    @if(!isset($coordinator->name))
                        <td>{{ $data->coordinator_code ?? null }}</td>
                    @endif
                    <td>{{ $data->volunteer_name ?? null }}</td>
                    <td>RW {{ $data->rw ?? '-' }} | RT {{ $data->rt ?? '-' }}</td>
                    <td></td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>

<div class="page-break"></div>

<table width="100%" style="position: absolute;bottom: 170px;">
    <thead>
        <tr>
            <td></td>
            <td width="200px">
                Approved
                <table width="200px" class="table list_content">
                    <thead>
                        <tr>
                            <td height="60px"></td>
                        </tr>
                        <tr>
                            <td height="60px"></td>
                        </tr>
                    </thead>
                </table>
            </td>
        </tr>
    </thead>
</table>

<div style="background:#fff;position:absolute;bottom:-30px;height:30px;width:100%;"></div>

<br><br>
<table width="100%">
    <thead>
        <tr>
            <td style="width:140px">
                <img src="https://i.ibb.co/5vPLSRT/smartrio.png" style="width:130px">
            </td>
            <td style="font-size:27px">
                <b>Muhammad Satrio<br>Adi Negoro</b>
                <br>
                Data Simpatisan TPS
            </td>
            <td width="35%" class="text-end" style="font-size:25px">
                <b>{{ $subdistrict->name ?? 'NA' }} | TPS {{ $no_tps }}</b>
            </td>
        </tr>
    </thead>
</table>

<br><br>
<table width="100%">
    <thead>
        <tr>
            <td class="text-end" width="20%">Kota/Kabupaten</td>
            <td width="2%" class="text-center">:</td>
            <td>{{ $subdistrict->district->city->name ?? 'NA' }}</td>
        </tr>
        <tr>
            <td class="text-end">Kecamatan</td>
            <td class="text-center">:</td>
            <td>{{ $subdistrict->district->name ?? 'NA' }}</td>
        </tr>
        <tr>
            <td class="text-end">TPS</td>
            <td class="text-center">:</td>
            <td>{{ $no_tps ?? 'NA' }}</td>
        </tr>
    </thead>
</table>
<?php
    unset($coordinators[""]);
    $coordinators = array_map('count', $coordinators);
    $coordinators_name = implode(', ', array_keys($coordinators));
?>
<br>
<table width="100%">
    <thead>
        <tr>
            <td width="15%" style="padding-left:20px">Koordinator :</td>
            <td>{{ $coordinators_name }}</td>
            <td colspan="3"></td>
        </tr>
        <tr>
            <td width="15%" style="padding-left:20px">Koordinator TPS :</td>
            <td>{{ $coordinator_tps->name ?? 'NA' }}</td>
            <td colspan="3"></td>
        </tr>
        <tr>
            <td width="15%" style="padding-left:20px">Kanvaser TPS :</td>
            <td></td>
            <td width="5%"><b>Total :</b></td>
            <td width="13%"><b>{{ $datas->count() }} Orang</b></td>
            <td width="48%"><b>RW | RT</b></td>
        </tr>
        @foreach($groupBykanvaser as $volunteer_name => $dataGroups)
            @foreach($dataGroups as $address => $data)
            <?php
                $rw_rt = explode('#',$address);
                $rw = $rw_rt[0];
                $rt = $rw_rt[1];
            ?>
                <tr>
                    <td></td>
                    <td>{{ $volunteer_name }}</td>
                    <td></td>
                    <td>{{ count($data) }} Simpatisan</td>
                    <td>RW {{ $rw }} | RT {{ $rt }}</td>
                </tr>
            @endforeach
        @endforeach
    </thead>
</table>