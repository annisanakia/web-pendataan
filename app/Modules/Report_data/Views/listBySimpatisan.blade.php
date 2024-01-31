<form method="GET" action="{{ url($controller_name) }}" accept-charset="UTF-8" class="form-validation-ajax">
<input type="hidden" name="model" value="{{ $model }}">
<input type="hidden" name="start_date" value="{{ $start_date }}">
<input type="hidden" name="end_date" value="{{ $end_date }}">
<input type="hidden" name="subdistrict_id" value="{{ $subdistrict_id }}">
<input type="hidden" name="coordinator_id" value="{{ $coordinator_id }}">
<input type="hidden" name="no_tps" value="{{ $no_tps }}">
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
                        Nama Simpatisan
                    </th>
                    <th class="text-center order-link {{ ($sort_field == 'whatsapp'? 'sort-'.(orders()[$sort_type] ?? null) : null) }}" href="{{ url($controller_name.'/getData?sort_field=whatsapp&sort_type='.($sort_field == 'whatsapp'? $sort_type : 0)+1) }}">
                        No Telepon
                    </th>
                    @if(!isset($coordinator->name))
                        <th class="text-center order-link {{ ($sort_field == 'coordinator_name'? 'sort-'.(orders()[$sort_type] ?? null) : null) }}" href="{{ url($controller_name.'/getData?sort_field=coordinator_name&sort_type='.($sort_field == 'coordinator_name'? $sort_type : 0)+1) }}">
                            Koordinator
                        </th>
                    @endif
                    <th class="text-center order-link {{ ($sort_field == 'volunteer_name'? 'sort-'.(orders()[$sort_type] ?? null) : null) }}" href="{{ url($controller_name.'/getData?sort_field=volunteer_name&sort_type='.($sort_field == 'volunteer_name'? $sort_type : 0)+1) }}">
                        Kanvaser
                    </th>
                    <th class="text-center order-link {{ ($sort_field == 'rt'? 'sort-'.(orders()[$sort_type] ?? null) : null) }}" href="{{ url($controller_name.'/getData?sort_field=rt&sort_type='.($sort_field == 'rt'? $sort_type : 0)+1) }}">
                        RT
                    </th>
                    <th class="text-center order-link {{ ($sort_field == 'rw'? 'sort-'.(orders()[$sort_type] ?? null) : null) }}" href="{{ url($controller_name.'/getData?sort_field=rw&sort_type='.($sort_field == 'rw'? $sort_type : 0)+1) }}">
                        RW
                    </th>
                </tr>
                <tr>
                    <th><button type="submit" class="btn"><i class="fas fa-search"></i></span></button></th>
                    <th><input type="text" name="filter[nik]" value="{{ $param['filter']['nik'] ?? null }}" class="form-control"></th>
                    <th><input type="text" name="filter[name]" value="{{ $param['filter']['name'] ?? null }}" class="form-control"></th>
                    <th><input type="text" name="filter[whatsapp]" value="{{ $param['filter']['whatsapp'] ?? null }}" class="form-control"></th>
                    @if(!isset($coordinator->name))
                        <th><input type="text" name="filter[coordinator_name]" value="{{ $param['filter']['coordinator_name'] ?? null }}" class="form-control"></th>
                    @endif
                    <th><input type="text" name="filter[volunteer_name]" value="{{ $param['filter']['volunteer_name'] ?? null }}" class="form-control"></th>
                    <th><input type="text" name="filter[rt]" value="{{ $param['filter']['rt'] ?? null }}" class="form-control"></th>
                    <th><input type="text" name="filter[rw]" value="{{ $param['filter']['rw'] ?? null }}" class="form-control"></th>
                </tr>
            </thead>
            <tbody>
                @if(count($datas) <= 0)
                    <tr>
                        <td colspan="{{ isset($coordinator->name)? 7 : 8 }}" class="text-center">Data Tidak Ditemukan</td>
                    </tr>
                @else
                    @php $i=0 @endphp
                    @foreach($datas as $data)
                    <tr>
                        <td class="text-center">{{ (($datas->currentPage() - 1 ) * $datas->perPage() ) + ++$i }}</td>
                        <td>{{ $data->nik ?? null }}</td>
                        <td>{{ strtoupper($data->name ?? null) }}</td>
                        <td>{{ $data->whatsapp ?? null }}</td>
                        @if(!isset($coordinator->name))
                            <td>{{ $data->coordinator_name ?? null }}</td>
                        @endif
                        <td>{{ $data->volunteer_name ?? null }}</td>
                        <td>{{ $data->rt ?? null }}</td>
                        <td>{{ $data->rw ?? null }}</td>
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