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
                    Grafik Total Data Koordinator
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
                    <th class="text-center order-link {{ ($sort_field == 'name'? 'sort-'.(orders()[$sort_type] ?? null) : null) }}" href="{{ url($controller_name.'/getData?sort_field=name&sort_type='.($sort_field == 'name'? $sort_type : 0)+1) }}">
                        Nama
                    </th>
                    <th class="text-center order-link {{ ($sort_field == 'verif'? 'sort-'.(orders()[$sort_type] ?? null) : null) }}" href="{{ url($controller_name.'/getData?sort_field=verif&sort_type='.($sort_field == 'verif'? $sort_type : 0)+1) }}">
                        Terverifikasi
                    </th>
                    <th class="text-center order-link {{ ($sort_field == 'data'? 'sort-'.(orders()[$sort_type] ?? null) : null) }}" href="{{ url($controller_name.'/getData?sort_field=data&sort_type='.($sort_field == 'data'? $sort_type : 0)+1) }}">
                        Total Data
                    </th>
                </tr>
                <tr>
                    <th><button type="submit" class="btn"><i class="fas fa-search"></i></span></button></th>
                    <th><input type="text" name="filter[name]" value="{{ $param['filter']['name'] ?? null }}" class="form-control"></th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @php
                    $i=0;
                    $dataByCoordinators = [];
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
                        $collection_data = $data->collections_data;
                        $verifikasi = $collections_verif[$data->id] ?? 0;
                        $dataByCoordinators[] = $collection_data->count();

                        $collections_subdistrict = \Models\collection_data::select('subdistrict_id', \DB::raw("count(id) as total"))->where('coordinator_id',$data->id)
                                ->groupBy('subdistrict_id')->get()
                                ->pluck('total','subdistrict_id')->all();
                        $collections_subdistrict_verif = \Models\collection_data::select('subdistrict_id', \DB::raw("count(id) as total"))->where('coordinator_id',$data->id)
                                ->where('status',2)->groupBy('subdistrict_id')->get()
                                ->pluck('total','subdistrict_id')->all();

                        $total_verifikasi += $verifikasi;
                        $total += $collection_data->count();
                    ?>
                    <tr>
                        <td class="text-center">{{ (($datas->currentPage() - 1 ) * $datas->perPage() ) + ++$i }}</td>
                        <td>{{ $data->name }}</td>
                        <td class="text-center">{{ $verifikasi }}</td>
                        <td class="text-center">{{ $collection_data->count() }}</td>
                    </tr>
                    @foreach($data->users_subdistrict as $row)
                        <?php
                            $collection_data = $collections_subdistrict[$row->subdistrict_id] ?? 0;
                            $verifikasi = $collections_subdistrict_verif[$row->subdistrict_id] ?? 0;
                        ?>
                        <tr>
                            <td class="text-center"></td>
                            <td>{{ $row->subdistrict->name ?? null }}</td>
                            <td class="text-center">{{ $verifikasi }}</td>
                            <td class="text-center">{{ $collection_data }}</td>
                        </tr>
                    @endforeach
                    @endforeach
                @endif
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="2" class="text-center">Subtotal</th>
                    <th class="text-center">{{ $total_verifikasi }}</th>
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
        labels: {!! json_encode($coordinators) !!},
        datasets: [{
        label: "Rekap Koordinator",
        backgroundColor: "rgba(2,117,216,1)",
        borderColor: "rgba(2,117,216,1)",
        data: {!! json_encode($dataByCoordinators) !!},
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
            maxTicksLimit: 20
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