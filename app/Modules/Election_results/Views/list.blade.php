<form method="GET" action="{{ url($controller_name) }}" accept-charset="UTF-8">

<div class="card">
    <div class="card-body">
        <div class="d-grid gap-2 d-flex justify-content-end mt-4">
            @include('component.actions')
        </div>
        <div class="select-max-row d-inline-block mt-2">
            Show 
            <input type="text" name="max_row" value="{{ $datas->perPage() }}" size="4" maxlength="4" class="text-center"> entries
        </div>
        <!-- Table with stripped rows -->
        <?php
            $groups_id = Auth::user()->groups_id ?? null;
        ?>
        <div class="table-responsive">
        <table class="table table-striped mt-3">
            <thead>
                <tr>
                    <th width="5%" class="text-center">No</th>
                    <th width="100px" class="order-link {{ ($sort_field == 'district_name'? 'sort-'.(orders()[$sort_type] ?? null) : null) }}">
                        <a href="{{ url($controller_name.'?sort_field=district_name&sort_type='.(($sort_field == 'district_name'? $sort_type : 0)+1).'&'.http_build_query($url_param)) }}">
                            Kecamatan
                        </a>
                    </th>
                    <th width="100px" class="order-link {{ ($sort_field == 'subdistrict_name'? 'sort-'.(orders()[$sort_type] ?? null) : null) }}">
                        <a href="{{ url($controller_name.'?sort_field=subdistrict_name&sort_type='.(($sort_field == 'subdistrict_name'? $sort_type : 0)+1).'&'.http_build_query($url_param)) }}">
                            Kelurahan
                        </a>
                    </th>
                    <th width="100px" class="order-link {{ ($sort_field == 'no_tps'? 'sort-'.(orders()[$sort_type] ?? null) : null) }}">
                        <a href="{{ url($controller_name.'?sort_field=no_tps&sort_type='.(($sort_field == 'no_tps'? $sort_type : 0)+1).'&'.http_build_query($url_param)) }}">
                            No TPS
                        </a>
                    </th>
                    <th width="100px" class="order-link {{ ($sort_field == 'total_result'? 'sort-'.(orders()[$sort_type] ?? null) : null) }}">
                        <a href="{{ url($controller_name.'?sort_field=total_result&sort_type='.(($sort_field == 'total_result'? $sort_type : 0)+1).'&'.http_build_query($url_param)) }}">
                            Total Hasil
                        </a>
                    <th width="12%" class="text-center">Aksi</th>
                </tr>
                <tr>
                    <th><button type="submit" class="btn"><i class="fas fa-search"></i></span></button></th>
                    <th><input type="text" name="filter[district_name]" value="{{ $param['filter']['district_name'] ?? null }}" class="form-control"></th>
                    <th><input type="text" name="filter[subdistrict_name]" value="{{ $param['filter']['subdistrict_name'] ?? null }}" class="form-control"></th>
                    <th><input type="text" name="filter[no_tps]" value="{{ $param['filter']['no_tps'] ?? null }}" class="form-control"></th>
                    <th><input type="text" name="filter[total_result]" value="{{ $param['filter']['total_result'] ?? null }}" class="form-control"></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @if(count($datas) <= 0)
                    <tr>
                        <td colspan="6" class="text-center">Data Tidak Ditemukan</td>
                    </tr>
                @else
                    @php $i=0 @endphp
                    @foreach($datas as $data)
                    <tr>
                        <td class="text-center">{{ (($datas->currentPage() - 1 ) * $datas->perPage() ) + ++$i }}</td>
                        <td>{{ $data->district->name ?? null }}</td>
                        <td>{{ $data->subdistrict->name ?? null }}</td>
                        <td>{{ $data->no_tps }}</td>
                        <td>{{ $data->total_result }}</td>
                        <td class="action text-center" nowrap>
                            <a class="btn btn-primary px-2 py-1" href="{{ url($controller_name.'/edit/'.$data->id) }}">
                                <i class="fa-solid fa-pencil"></i>
                            </a>
                            @if($groups_id != 3)
                                <a class="btn btn-danger px-2 py-1 delete" data-name="{{ $data->name }}" href="{{ url($controller_name.'/delete/'.$data->id) }}">
                                    <i class="fa-solid fa-trash"></i>
                                </a>
                            @endif
                        </td>
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
    </div>
</div>

</form>