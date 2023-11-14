@extends('layouts.app')
@section('content_app')
<main class="bg-softblue min-vh-100">
    <div class="container">
        <h1 class="pt-4">Data Monitoring</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Data Monitoring</li>
        </ol>
        <section class="section">
            <div class="row">
                <?php
                    $colors = ['#4e88d0', '#64b672', '#c05252', '#dd793c', '#52c8c1'];
                    $total_all_data = 0;
                ?>
                @foreach($districts as $key => $district)
                <?php
                    $key = $key > 5? $key-5 : $key;
                    $total = $collection_datas->where('district_id',$district->id)->count() ?? 0;
                    $total_target = $district->subdistrict->sum('target') ?? 0;
                    $total_all_data += $total;
                ?>
                <div class="col-md">
                    <div class="card bg-primary text-white mb-4 text-center" style="background-color: {{ $colors[$key] ?? '#4e88d0' }} !important;">
                        <div class="card-header"><b>{{ $district->name }}</b></div>
                        <div class="card-body">{{ $total }} / {{ $total_target }} Data</div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="row">
                <div class="col-md">
                    <div class="card bg-primary text-white mb-4 text-center" style="background-color: #52c8c1 !important;">
                        <div class="card-header border-0">
                            <b>Total Semua Data</b> : {{ $total_all_data }} Data
                        </div>
                    </div>
                </div>
                <div class="col-md">
                    <div class="card bg-primary text-white mb-4 text-center" style="background-color: #4e88d0 !important;">
                        <div class="card-header border-0">
                            <b>Total Semua Data Hari ini</b> : {{ array_sum($dataByToday) }} Data
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-6">
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-chart-bar me-1"></i>
                            Grafik Pendataan Target
                        </div>
                        <div class="card-body">
                            @include(ucwords($controller_name).'::getDataTargetGraph')
                        </div>
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-chart-bar me-1"></i>
                            Grafik Pendataan Status
                        </div>
                        <div class="card-body">
                            @include(ucwords($controller_name).'::getDataStatusGraph')
                        </div>
                    </div>
                </div>
                <div class="col-xl-12">
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-chart-bar me-1"></i>
                            Grafik Pendataan Hari Ini
                        </div>
                        <div class="card-body">
                            @include(ucwords($controller_name).'::getDataTodayGraph')
                        </div>
                    </div>
                </div>
                <div class="col-xl-12">
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-chart-bar me-1"></i>
                            Grafik Pendataan Kelurahan
                        </div>
                        <div class="card-body">
                            @include(ucwords($controller_name).'::getDataSubdistrictTargetGraph')
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-md-6">
                    <label class="form-label">Kecamatan</label>
                    <select class="form-select" name="district_id">
                        @foreach($districts as $key => $row)
                            <option value="{{ $row->id }}" {{ $key == 0? 'selected' : '' }}>{{ $row->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div id="getData" class="position-relative" style="min-height: 200px;"></div>
        </section>
    </div>
</main>
@endsection

@section('scripts')
<script src="{{ asset('assets/js/chart.min.js') }}" crossorigin="anonymous"></script>
<script type="text/javascript">
    getData($('select[name=district_id]').val());
    function getData(district_id){

        $('#getData').append('<div class="loader"><img src="{{asset("assets/images/loading.gif")}}" /></div>');

        $.ajax({
            url: "{{ url($controller_name.'/getDataMonitoring') }}",
            type: 'GET',
            data: {district_id : district_id},
            success: function(data) { 
                $('#getData').html(data);
            },
            error: function (e) {
                swalDeleteButtons.fire(
                    'Warning !!',
                    'Terjadi Kesalahan Data',
                    'error'
                )
            }
        });
    }

    $('select[name=district_id]').change(function() {
        getData($(this).val());
    });
</script>
@endsection