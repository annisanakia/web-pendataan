<form method="GET" action="{{ url($controller_name) }}" accept-charset="UTF-8" class="form-validation-ajax">
<input type="hidden" name="model" value="{{ $model }}">
<input type="hidden" name="start_date" value="{{ $start_date }}">
<input type="hidden" name="end_date" value="{{ $end_date }}">
<input type="hidden" name="sort_field" value="{{ $sort_field }}" class="order-input">
<input type="hidden" name="sort_type" value="{{ $sort_type }}" class="order-input">
<div class="card mt-4">
    <div class="card-body">
        <div class="col-xl-12">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chart-bar me-1"></i>
                    Grafik Mingguan Koordinator
                </div>
                <div class="card-body"><canvas id="myBarChart" width="100%" height="40"></canvas></div>
            </div>
        </div>
        <div class="d-grid gap-2 d-flex justify-content-end mt-4">
            @include('component.actions')
        </div>
        <div class="select-max-row d-inline-block">
            Show 
            <input type="text" name="max_row" value="{{ $datas->perPage() }}" size="4" maxlength="4" class="text-center"> entries
        </div>
        <!-- Table with stripped rows -->
        <div class="table-responsive">
        <table class="table table-striped mt-3">
            <thead>
                <tr>
                    <th width="5%" class="text-center">No</th>
                    <th class="text-center order-link {{ ($sort_field == 'nik'? 'sort-'.(orders()[$sort_type] ?? null) : null) }}" href="{{ url($controller_name.'/getData?sort_field=nik&sort_type='.($sort_field == 'nik'? $sort_type : 0)+1) }}">
                        NIK
                    </th>
                    <th class="text-center order-link {{ ($sort_field == 'name'? 'sort-'.(orders()[$sort_type] ?? null) : null) }}" href="{{ url($controller_name.'/getData?sort_field=name&sort_type='.($sort_field == 'name'? $sort_type : 0)+1) }}">
                        Nama Lengkap
                    </th>
                    <th class="text-center order-link {{ ($sort_field == 'subdistrict_name'? 'sort-'.(orders()[$sort_type] ?? null) : null) }}" href="{{ url($controller_name.'/getData?sort_field=subdistrict_name&sort_type='.($sort_field == 'subdistrict_name'? $sort_type : 0)+1) }}">
                        Kelurahan
                    </th>
                    <th class="text-center order-link {{ ($sort_field == 'no_tps'? 'sort-'.(orders()[$sort_type] ?? null) : null) }}" href="{{ url($controller_name.'/getData?sort_field=no_tps&sort_type='.($sort_field == 'no_tps'? $sort_type : 0)+1) }}">
                        TPS
                    </th>
                    <th class="text-center order-link {{ ($sort_field == 'coordinator_name'? 'sort-'.(orders()[$sort_type] ?? null) : null) }}" href="{{ url($controller_name.'/getData?sort_field=coordinator_name&sort_type='.($sort_field == 'coordinator_name'? $sort_type : 0)+1) }}">
                        Koordinator
                    </th>
                    <th class="text-center order-link {{ ($sort_field == 'volunteer_name'? 'sort-'.(orders()[$sort_type] ?? null) : null) }}" href="{{ url($controller_name.'/getData?sort_field=volunteer_name&sort_type='.($sort_field == 'volunteer_name'? $sort_type : 0)+1) }}">
                        Relawan Data
                    </th>
                    <th width="13%" class="text-center order-link {{ ($sort_field == 'status'? 'sort-'.(orders()[$sort_type] ?? null) : null) }}" href="{{ url($controller_name.'/getData?sort_field=status&sort_type='.($sort_field == 'status'? $sort_type : 0)+1) }}">
                        Status
                    </th>
                    <th width="13%" class="text-center order-link {{ ($sort_field == 'status_share'? 'sort-'.(orders()[$sort_type] ?? null) : null) }}" href="{{ url($controller_name.'/getData?sort_field=status_share&sort_type='.($sort_field == 'status_share'? $sort_type : 0)+1) }}">
                        Status<br>Dibagikan
                    </th>
                    <th class="text-center order-link {{ ($sort_field == 'created_at'? 'sort-'.(orders()[$sort_type] ?? null) : null) }}" href="{{ url($controller_name.'/getData?sort_field=created_at&sort_type='.($sort_field == 'created_at'? $sort_type : 0)+1) }}">
                        Tanggal<br> Data Masuk
                    </th>
                </tr>
                <tr>
                    <th><button type="submit" class="btn"><i class="fas fa-search"></i></span></button></th>
                    <th><input type="text" name="filter[nik]" value="{{ $param['filter']['nik'] ?? null }}" class="form-control"></th>
                    <th><input type="text" name="filter[name]" value="{{ $param['filter']['name'] ?? null }}" class="form-control"></th>
                    <th><input type="text" name="filter[subdistrict_name]" value="{{ $param['filter']['subdistrict_name'] ?? null }}" class="form-control"></th>
                    <th><input type="text" name="filter[no_tps]" value="{{ $param['filter']['no_tps'] ?? null }}" class="form-control"></th>
                    <th><input type="text" name="filter[coordinator_name]" value="{{ $param['filter']['coordinator_name'] ?? null }}" class="form-control"></th>
                    <th><input type="text" name="filter[volunteer_name]" value="{{ $param['filter']['volunteer_name'] ?? null }}" class="form-control"></th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @if(count($datas) <= 0)
                    <tr>
                        <td colspan="10" class="text-center">Data Tidak Ditemukan</td>
                    </tr>
                @else
                    @php $i=0 @endphp
                    @foreach($datas as $data)
                    <tr>
                        <td class="text-center">{{ (($datas->currentPage() - 1 ) * $datas->perPage() ) + ++$i }}</td>
                        <td>{{ $data->nik ?? null }}</td>
                        <td>{{ strtoupper($data->name ?? null) }}</td>
                        <td>{{ $data->subdistrict->name ?? null }}</td>
                        <td>{{ $data->no_tps ?? null }}</td>
                        <td>{{ $data->coordinator->name ?? null }}</td>
                        <td>{{ $data->volunteer_data->name ?? null }}</td>
                        <td nowrap>
                            <a class="btn btn-{{ statusColor()[$data->status] ?? null }} px-2 py-1 f-14px">
                                {{ status()[$data->status] ?? null }}
                            </a>
                        </td>
                        <td nowrap>
                            <a class="btn btn-{{ status_shareColor()[$data->status_share] ?? null }} px-2 py-1 f-14px {{ $data->status != 2? 'disabled' : '' }}">
                                {{ status_share()[$data->status_share] ?? null }}
                            </a>
                        </td>
                        <td nowrap>{{ dateToIndo($data->created_at ?? null) }}</td>
                    </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
        </div>
        <!-- End Table with stripped rows -->
        <div class="table-list-footer">
            <span class="result-count">Showing {{$datas->firstItem()}} to {{$datas->lastItem()}} of {{$datas->total()}} entries</span>
            {{ $datas->onEachSide(0)->appends($param)->links('component.pagination')}}        
        </div>
        <?php
            // dd($subdistricts,$dataBySubdistrict);
        ?>
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
    $(".order-link").click(function (e) {
        e.preventDefault();
        var url = $(this).attr('href');
        getDataDetail(url,$('.form-validation-ajax :not(.order-input)').serialize());
    });
    function getDataDetail(url,data){
        $('#getData').append('<div class="loader"><img src="{{asset("assets/images/loading.gif")}}" /></div>');
        $.ajax({
            url: url,
            type: 'GET',
            data: data,
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
    // Set new default font family and font color to mimic Bootstrap's default styling
    Chart.defaults.global.defaultFontFamily = '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
    Chart.defaults.global.defaultFontColor = '#292b2c';

    // Bar Chart Example
    var ctx = document.getElementById("myBarChart");
    var myLineChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: {!! json_encode($dates) !!},
        datasets: [{
        label: "Rekap",
        backgroundColor: "rgba(2,117,216,1)",
        borderColor: "rgba(2,117,216,1)",
        data: {!! json_encode($dataByDates) !!},
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
            maxTicksLimit: 25
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