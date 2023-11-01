<div class="row">
    <?php
        $groups_id = \Auth::user()->groups_id ?? null;
    ?>
    <div class="col-xl-6">
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-chart-area me-1"></i>
                Grafik Mingguan
            </div>
            <div class="card-body">
                @include(ucwords($controller_name).'::getDataGraph')
            </div>
        </div>
    </div>
    @if($groups_id != 2)
    <div class="col-xl-6">
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-chart-bar me-1"></i>
                Grafik Mingguan Koordinator
            </div>
            <div class="card-body">
                @include(ucwords($controller_name).'::getDataCoorGraph')
            </div>
        </div>
    </div>
    @else
    <div class="col-xl-6">
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-chart-bar me-1"></i>
                Grafik Mingguan Status
            </div>
            <div class="card-body">
                @include(ucwords($controller_name).'::getDataStatusGraph')
            </div>
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
                        <th>Nama Lengkap</th>
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
                            <td>{{ strtoupper($data->name ?? null) }}</td>
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
</script>