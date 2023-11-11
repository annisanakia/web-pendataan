<form method="GET" action="{{ url($controller_name) }}" accept-charset="UTF-8" class="form-validation-ajax">
<input type="hidden" name="model" value="{{ $model }}">
<input type="hidden" name="start_date" value="{{ $start_date }}">
<input type="hidden" name="end_date" value="{{ $end_date }}">
<input type="hidden" name="subdistrict_id" value="{{ $subdistrict_id }}">
<div class="card mt-4">
    <div class="card-body">
        <div class="col-xl-12">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chart-bar me-1"></i>
                    Grafik Pendataan Tiap TPS
                </div>
                <div class="card-body"><canvas id="myBarChart" width="100%" height="40"></canvas></div>
            </div>
        </div>
        <div class="d-grid gap-2 d-flex justify-content-end mt-4">
            @include('component.actions')
        </div>
        <div class="select-max-row d-inline-block mt-2">
            Show 
            <input type="text" name="max_row" value="{{ $datas->perPage() }}" size="4" maxlength="4" class="text-center"> entries
        </div>
        <!-- Table with stripped rows -->
        <div class="table-responsive">
        <table class="table table-striped mt-3">
            <thead>
                <tr>
                    <th width="5%" class="text-center">No</th>
                    <th>No TPS</th>
                    <th class="text-center">Terverifikasi</th>
                    <th class="text-center">Sudah Dibagikan</th>
                    <th class="text-center">Total Data</th>
                </tr>
                <tr>
                    <th><button type="submit" class="btn"><i class="fas fa-search"></i></span></button></th>
                    <th><input type="text" name="filter[no_tps]" value="{{ $param['filter']['no_tps'] ?? null }}" class="form-control"></th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @php
                    $i=0;
                    $total_verifikasi = 0;
                    $total_dibagikan = 0;
                    $total = 0;
                @endphp
                @if(count($datas) <= 0)
                    <tr>
                        <td colspan="5" class="text-center">Data Tidak Ditemukan</td>
                    </tr>
                @else
                    @foreach($datas as $data)
                    <?php
                        $collection_data = $collection_datas->where('no_tps',$data->no_tps);
                        $verifikasi = $collection_data->where('status',2);
                        $dibagikan = $collection_data->where('status_share',2);

                        $total_verifikasi += $verifikasi->count();
                        $total_dibagikan += $dibagikan->count();
                        $total += $collection_data->count();
                    ?>
                    <tr>
                        <td class="text-center">{{ (($datas->currentPage() - 1 ) * $datas->perPage() ) + ++$i }}</td>
                        <td>{{ $data->no_tps }}</td>
                        <td class="text-center">{{ $verifikasi->count() }}</td>
                        <td class="text-center">{{ $dibagikan->count() }}</td>
                        <td class="text-center">{{ $collection_data->count() }}</td>
                    </tr>
                    @endforeach
                @endif
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="2" class="text-center">Subtotal</th>
                    <th class="text-center">{{ $total_verifikasi }}</th>
                    <th class="text-center">{{ $total_dibagikan }}</th>
                    <th class="text-center">{{ $total }}</th>
                </tr>
            </tfoot>
        </table>
        </div>
        <!-- End Table with stripped rows -->
        <div class="table-list-footer">
            <span class="result-count">Showing {{$datas->firstItem()}} to {{$datas->lastItem()}} of {{$datas->total()}} entries</span>
            {{ $datas->onEachSide(0)->appends($param)->links('component.pagination')}}        
        </div>
    </div>
</div>

</form>

<script type="text/javascript">
    $(".form-validation-ajax").submit(function (e) {
        e.preventDefault();
        getDataDetail("{{ url($controller_name.'/getData') }}",$('.form-validation-ajax').serialize());
    });
    $(".page-link").click(function (e) {
        e.preventDefault();
        var url = $(this).attr('href');
        getDataDetail(url,$('.form-validation-ajax').serialize());
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

    // Bar Chart Example
    var ctx = document.getElementById("myBarChart");
    var myLineChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: {!! json_encode($no_tps) !!},
        datasets: [{
        label: "Rekap TPS",
        backgroundColor: "rgba(2,117,216,1)",
        borderColor: "rgba(2,117,216,1)",
        data: {!! json_encode($dataByTPS) !!},
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
            maxTicksLimit: 30
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
</script>