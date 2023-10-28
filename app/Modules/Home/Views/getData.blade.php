<div class="row">
    <?php
        $groups_id = \Auth::user()->groups_id ?? null;
    ?>
    <div class="{{ $groups_id == 2? 'col-xl-12' : 'col-xl-6' }}">
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-chart-area me-1"></i>
                Grafik Mingguan
            </div>
            <div class="card-body"><canvas id="myAreaChart" width="100%" height="40"></canvas></div>
        </div>
    </div>
    @if($groups_id != 2)
    <div class="col-xl-6">
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-chart-bar me-1"></i>
                Grafik Mingguan Koordinator
            </div>
            <div class="card-body"><canvas id="myBarChart" width="100%" height="40"></canvas></div>
        </div>
    </div>
    @endif
</div>
<div class="card mb-4">
    <div class="card-header">
        <i class="fas fa-table me-1"></i>
        Pendataan Hari ini
    </div>
    <div class="card-body">
        <form method="GET" action="{{ url($controller_name).'/getData' }}" accept-charset="UTF-8" class="form-validation">
            <input name="district_id" value="{{ $district_id }}" type="hidden">
            <div class="select-max-row d-inline-block mt-2">
                Show 
                <input type="text" name="max_row" value="{{ $collection_datas->perPage() }}" size="4" maxlength="4" class="text-center"> entries
            </div>
            <div class="table-responsive">
            <table class="table table-striped mt-3">
                <thead>
                    <tr>
                        <th width="5%" class="text-center">No</th>
                        <th>NIK</th>
                        <th>Name</th>
                        <th>Kelurahan</th>
                        <th>TPS</th>
                        <th>Koordinator</th>
                    </tr>
                    <tr>
                        <th><button type="submit" class="btn"><i class="fas fa-search"></i></span></button></th>
                        <th><input type="text" name="filter[nik]" value="{{ $param['filter']['nik'] ?? null }}" class="form-control"></th>
                        <th><input type="text" name="filter[name]" value="{{ $param['filter']['name'] ?? null }}" class="form-control"></th>
                        <th><input type="text" name="filter[subdistrict_name]" value="{{ $param['filter']['subdistrict_name'] ?? null }}" class="form-control"></th>
                        <th><input type="text" name="filter[no_tps]" value="{{ $param['filter']['no_tps'] ?? null }}" class="form-control"></th>
                        <th><input type="text" name="filter[coordinator_name]" value="{{ $param['filter']['coordinator_name'] ?? null }}" class="form-control"></th>
                    </tr>
                </thead>
                <tbody>
                    @if(count($collection_datas) <= 0)
                        <tr>
                            <td colspan="6" class="text-center">Data Tidak Ditemukan</td>
                        </tr>
                    @else
                        @php $i=0 @endphp
                        @foreach($collection_datas as $data)
                        <tr>
                            <td class="text-center">{{ (($collection_datas->currentPage() - 1 ) * $collection_datas->perPage() ) + ++$i }}</td>
                            <td>{{ $data->nik ?? null }}</td>
                            <td>{{ $data->name ?? null }}</td>
                            <td>{{ $data->subdistrict->name ?? null }}</td>
                            <td>{{ $data->no_tps ?? null }}</td>
                            <td>{{ $data->coordinator->name ?? null }}</td>
                        </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
            </div>
            <!-- End Table with stripped rows -->
            <div class="table-list-footer">
                <span class="result-count">Showing {{$collection_datas->firstItem()}} to {{$collection_datas->lastItem()}} of {{$collection_datas->total()}} entries</span>
                {{ $collection_datas->appends($param)->links('component.pagination')}}        
            </div>
        </form>
    </div>
</div>

<script src="{{ asset('assets/js/chart.min.js') }}" crossorigin="anonymous"></script>

<script type="text/javascript">
    $(".form-validation").submit(function (e) {
        e.preventDefault();
        getDataDetail("{{ url($controller_name.'/getData') }}",$('.form-validation').serialize());
    });
    $(".page-link").click(function (e) {
        e.preventDefault();
        var url = $(this).attr('href');
        getDataDetail(url,$('.form-validation').serialize());
    });
    function getDataDetail(url,data){
        $.ajax({
            url: url,
            type: 'GET',
            data: data,
            success: function(data) { 
                $('#getData').html(data);
            }
        });
    }
    // Set new default font family and font color to mimic Bootstrap's default styling
    Chart.defaults.global.defaultFontFamily = '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
    Chart.defaults.global.defaultFontColor = '#292b2c';

    // Area Chart Example
    var ctx = document.getElementById("myAreaChart");
    var myLineChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: ["Senin", "Selasa", "Rabu", "Kamis", "Jum'at", "Sabtu", "Minggu"],
        datasets: [{
        label: "Rekap Mingguan",
        lineTension: 0.3,
        backgroundColor: "rgba(2,117,216,0.2)",
        borderColor: "rgba(2,117,216,1)",
        pointRadius: 5,
        pointBackgroundColor: "rgba(2,117,216,1)",
        pointBorderColor: "rgba(255,255,255,0.8)",
        pointHoverRadius: 5,
        pointHoverBackgroundColor: "rgba(2,117,216,1)",
        pointHitRadius: 50,
        pointBorderWidth: 2,
        data: {!! json_encode($dataByDay) !!},
        }],
    },
    options: {
        scales: {
        xAxes: [{
            time: {
            unit: 'date'
            },
            gridLines: {
            display: false
            },
            ticks: {
            maxTicksLimit: 7
            }
        }],
        yAxes: [{
            ticks: {
            min: 0,
            maxTicksLimit: 5
            },
            gridLines: {
            color: "rgba(0, 0, 0, .125)",
            }
        }],
        },
        legend: {
        display: false
        }
    }
    });
    @if($groups_id != 2)
    // Set new default font family and font color to mimic Bootstrap's default styling
    Chart.defaults.global.defaultFontFamily = '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
    Chart.defaults.global.defaultFontColor = '#292b2c';

    // Bar Chart Example
    var ctx = document.getElementById("myBarChart");
    var myLineChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: {!! json_encode($coordinators) !!},
        datasets: [{
            label: "Rekap Koordinator",
            backgroundColor: "rgba(2,117,216,1)",
            borderColor: "rgba(2,117,216,1)",
            data: {!! json_encode($dataByCoor) !!},
        }],
    },
    options: {
        scales: {
        xAxes: [{
            time: {
            unit: 'month'
            },
            gridLines: {
            display: false
            },
            ticks: {
            maxTicksLimit: 6
            }
        }],
        yAxes: [{
            ticks: {
            min: 0,
            maxTicksLimit: 5
            },
            gridLines: {
            display: true
            }
        }],
        },
        legend: {
        display: false
        }
    }
    });
    @endif
</script>