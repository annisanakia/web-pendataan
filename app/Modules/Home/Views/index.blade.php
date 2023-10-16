@extends('layouts.layout')
@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Dashboard</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Dashboard</li>
    </ol>
    <div class="row">
        <div class="col-xl-4 col-md-4">
            <div class="card bg-primary text-white mb-4 text-center" style="background-color: #4e88d0 !important;">
                <div class="card-header"><b>Total Data Masuk Hari Ini</b></div>
                <div class="card-body">2100</div>
            </div>
        </div>
        <div class="col-xl-4 col-md-4">
            <div class="card bg-primary text-white mb-4 text-center" style="background-color: #64b672 !important;">
                <div class="card-header"><b>Total Data Masuk Minggu Ini</b></div>
                <div class="card-body">2100</div>
            </div>
        </div>
        <div class="col-xl-4 col-md-4">
            <div class="card bg-primary text-white mb-4 text-center" style="background-color: #bc5959 !important;">
                <div class="card-header"><b>Total Data Masuk Bulan Ini</b></div>
                <div class="card-body">2100</div>
            </div>
        </div>
    </div>
    {{--
    <div class="row">
        <div class="col-xl-2 col-md-4">
            <div class="card bg-primary text-white mb-4 text-center">
                <div class="card-header"><b>Tebet</b></div>
                <div class="card-body">2100</div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4">
            <div class="card bg-warning text-white mb-4 text-center">
                <div class="card-header"><b>Mampang</b></div>
                <div class="card-body">1170</div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4">
            <div class="card bg-success text-white mb-4 text-center">
                <div class="card-header"><b>Pancoran</b></div>
                <div class="card-body">1993</div>
            </div>
        </div>
        <div class="col-xl-2 col-md-6">
            <div class="card bg-danger text-white mb-4 text-center">
                <div class="card-header"><b>PASAR MINGGU</b></div>
                <div class="card-body">2000</div>
            </div>
        </div>
        <div class="col-xl-2 col-md-6">
            <div class="card bg-info text-white mb-4 text-center">
                <div class="card-header"><b>Jagakarsa</b></div>
                <div class="card-body">1100</div>
            </div>
        </div>
        <div class="col-xl-2 col-md-6">
            <div class="card bg-info text-white mb-4 text-center">
                <div class="card-header"><b>Cilandak</b></div>
                <div class="card-body">1100</div>
            </div>
        </div>
        <div class="col-xl-2 col-md-6">
            <div class="card bg-info text-white mb-4 text-center">
                <div class="card-header"><b>Jagakarsa</b></div>
                <div class="card-body">1100</div>
            </div>
        </div>
    </div>
    --}}
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