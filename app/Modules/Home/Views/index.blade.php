@extends('layouts.layout')
@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Dashboard</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Dashboard</li>
    </ol>
    <div class="row">
        <?php
            $colors = ['#4e88d0', '#64b672', '#bc5959', '#d7834b', '#52c8c1'];
        ?>
        @foreach($districts as $key => $district)
        <?php
            $key = $key > 5? $key-5 : $key;
        ?>
        <div class="col-md">
            <div class="card bg-primary text-white mb-4 text-center" style="background-color: {{ $colors[$key] ?? '#4e88d0' }} !important;">
                <div class="card-header"><b>{{ $district->name }}</b></div>
                <div class="card-body">2100 Data</div>
            </div>
        </div>
        @endforeach
    </div>
    <div class="row mb-4">
        <div class="col-md-6">
            <label class="form-label">Kecamatan</label>
            <select class="form-select">
                <option>Tebet</option>
                <option>Mampang</option>
                <option>Pancoran</option>
                <option>Pasar Minggu</option>
                <option selected>Jagakarsa</option>
            </select>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chart-area me-1"></i>
                    Grafik Mingguan
                </div>
                <div class="card-body"><canvas id="myAreaChart" width="100%" height="40"></canvas></div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chart-bar me-1"></i>
                    Grafik Bulanan
                </div>
                <div class="card-body"><canvas id="myBarChart" width="100%" height="40"></canvas></div>
            </div>
        </div>
    </div>
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Pendataan Hari ini
        </div>
        <div class="card-body">
            <table id="datatablesSimple">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th>NIK</th>
                        <th>Name</th>
                        <th>Kecamatan</th>
                        <th>Kelurahan</th>
                        <th>Whatsapp</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th class="text-center">No</th>
                        <th>NIK</th>
                        <th>Name</th>
                        <th>Kecamatan</th>
                        <th>Kelurahan</th>
                        <th>Whatsapp</th>
                    </tr>
                </tfoot>
                <tbody>
                    <?php
                        $no = 0;
                    ?>
                    @for($i=0;$i<=20;$i++)
                    <tr>
                        <td class="text-center">{{ ++$no }}</td>
                        <td>3174096112900001</td>
                        <td>Tiger Nixon</td>
                        <td>Jagakarsa</td>
                        <td>Jagakarsa</td>
                        <td>08276777388</td>
                    </tr>
                    <tr>
                        <td >{{ ++$no }}</td>
                        <td>3174096112900002</td>
                        <td>Garrett Winters</td>
                        <td>Jagakarsa</td>
                        <td>Jagakarsa</td>
                        <td>08276777388</td>
                    </tr>
                    @endfor
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection