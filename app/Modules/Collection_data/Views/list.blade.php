<form method="GET" action="{{ url($controller_name) }}" accept-charset="UTF-8">
<input type="hidden" name="sort_field" value="{{ $sort_field }}" class="order-input">
<input type="hidden" name="sort_type" value="{{ $sort_type }}" class="order-input">

<div class="card">
    <div class="card-body">
        @if(Session::has('message_import'))
            <div class="alert alert-success" id="hideMe">{{ Session::get('message_import') }}</div>
        @endif
        <div class="d-grid gap-2 d-flex justify-content-end mt-4">
            @include('component.actions')
        </div>
        <div class="select-max-row d-inline-block mt-2">
            Show 
            <input type="text" name="max_row" value="{{ $datas->perPage() }}" size="4" maxlength="4" class="text-center"> entries
        </div>
        <!-- Table with stripped rows -->
        <?php
            $user_id = Auth::user()->id ?? null;
            $groups_id = Auth::user()->groups_id ?? null;
        ?>
        <div class="table-responsive">
        <table class="table table-striped mt-3">
            <thead>
                <tr>
                    <th width="10px" class="text-center">No</th>
                    <th class="order-link {{ ($sort_field == 'nik'? 'sort-'.(orders()[$sort_type] ?? null) : null) }}">
                        <a href="{{ url($controller_name.'?sort_field=nik&sort_type='.(($sort_field == 'nik'? $sort_type : 0)+1).'&'.http_build_query($url_param)) }}">
                            NIK
                        </a>
                    </th>
                    <th width="100px" class="order-link {{ ($sort_field == 'whatsapp'? 'sort-'.(orders()[$sort_type] ?? null) : null) }}">
                        <a href="{{ url($controller_name.'?sort_field=whatsapp&sort_type='.(($sort_field == 'whatsapp'? $sort_type : 0)+1).'&'.http_build_query($url_param)) }}">
                            No Whatsapp
                        </a>
                    </th>
                    <th width="100px" class="order-link {{ ($sort_field == 'name'? 'sort-'.(orders()[$sort_type] ?? null) : null) }}">
                        <a href="{{ url($controller_name.'?sort_field=name&sort_type='.(($sort_field == 'name'? $sort_type : 0)+1).'&'.http_build_query($url_param)) }}">
                            Nama Lengkap
                        </a>
                    </th>
                    <th width="100px" class="order-link {{ ($sort_field == 'coordinator_name'? 'sort-'.(orders()[$sort_type] ?? null) : null) }}">
                        <a href="{{ url($controller_name.'?sort_field=coordinator_name&sort_type='.(($sort_field == 'coordinator_name'? $sort_type : 0)+1).'&'.http_build_query($url_param)) }}">
                            Koordinator
                        </a>
                    </th>
                    <th width="100px" class="order-link {{ ($sort_field == 'volunteer_name'? 'sort-'.(orders()[$sort_type] ?? null) : null) }}">
                        <a href="{{ url($controller_name.'?sort_field=volunteer_name&sort_type='.(($sort_field == 'volunteer_name'? $sort_type : 0)+1).'&'.http_build_query($url_param)) }}">
                            Relawan Data
                        </a>
                    </th>
                    <th width="100px" class="order-link {{ ($sort_field == 'subdistrict_name'? 'sort-'.(orders()[$sort_type] ?? null) : null) }}">
                        <a href="{{ url($controller_name.'?sort_field=subdistrict_name&sort_type='.(($sort_field == 'subdistrict_name'? $sort_type : 0)+1).'&'.http_build_query($url_param)) }}">
                            Kelurahan
                        </a>
                    </th>
                    <th width="50px" class="order-link {{ ($sort_field == 'no_tps'? 'sort-'.(orders()[$sort_type] ?? null) : null) }}">
                        <a href="{{ url($controller_name.'?sort_field=no_tps&sort_type='.(($sort_field == 'no_tps'? $sort_type : 0)+1).'&'.http_build_query($url_param)) }}">
                            TPS
                        </a>
                    </th>
                    <th width="100px" class="order-link {{ ($sort_field == 'status'? 'sort-'.(orders()[$sort_type] ?? null) : null) }}">
                        <a href="{{ url($controller_name.'?sort_field=status&sort_type='.(($sort_field == 'status'? $sort_type : 0)+1).'&'.http_build_query($url_param)) }}">
                            Status
                        </a>
                    </th>
                    <th width="100px" class="order-link {{ ($sort_field == 'status_share'? 'sort-'.(orders()[$sort_type] ?? null) : null) }}">
                        <a href="{{ url($controller_name.'?sort_field=status_share&sort_type='.(($sort_field == 'status_share'? $sort_type : 0)+1).'&'.http_build_query($url_param)) }}">
                            Status<br>Dibagikan
                        </a>
                    </th>
                    <th width="100px" class="text-center">Aksi</th>
                </tr>
                <tr>
                    <th><button type="submit" class="btn"><i class="fas fa-search"></i></span></button></th>
                    <th><input type="text" name="filter[nik]" value="{{ $param['filter']['nik'] ?? null }}" class="form-control"></th>
                    <th><input type="text" name="filter[whatsapp]" value="{{ $param['filter']['whatsapp'] ?? null }}" class="form-control"></th>
                    <th><input type="text" name="filter[name]" value="{{ $param['filter']['name'] ?? null }}" class="form-control"></th>
                    <th><input type="text" name="filter[coordinator_name]" value="{{ $param['filter']['coordinator_name'] ?? null }}" class="form-control"></th>
                    <th><input type="text" name="filter[volunteer_name]" value="{{ $param['filter']['volunteer_name'] ?? null }}" class="form-control"></th>
                    <th><input type="text" name="filter[subdistrict_name]" value="{{ $param['filter']['subdistrict_name'] ?? null }}" class="form-control"></th>
                    <th><input type="text" name="filter[no_tps]" value="{{ $param['filter']['no_tps'] ?? null }}" class="form-control"></th>
                    <th>
                        <select name="filter[status]" class="form-select">
                            <option value="" selected>-- Pilih --</option>
                            @foreach(status() as $key => $status)
                                <option value="{{ $key }}" {{ $key == ($param['filter']['status'] ?? null)? 'selected' : '' }}>{{ $status }}</option>
                            @endforeach
                        </select>
                    </th>
                    <th>
                        <select name="filter[status_share]" class="form-select">
                            <option value="" selected>-- Pilih --</option>
                            @foreach(status_share() as $key => $status)
                                <option value="{{ $key }}" {{ $key == ($param['filter']['status_share'] ?? null)? 'selected' : '' }}>{{ $status }}</option>
                            @endforeach
                        </select>
                    </th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @if(count($datas) <= 0)
                    <tr>
                        <td colspan="11" class="text-center">Data Tidak Ditemukan</td>
                    </tr>
                @else
                    @php $i=0 @endphp
                    @foreach($datas as $data)
                    <?php
                        $first_character = mb_substr($data->whatsapp, 0, 1);
                        $whatsapp = $first_character == 0? '+62'.substr($data->whatsapp, 1) : $data->whatsapp;
                    ?>
                    <tr>
                        <td class="text-center">{{ (($datas->currentPage() - 1 ) * $datas->perPage() ) + ++$i }}</td>
                        <td>{{ $data->nik }}</td>
                        <td><a href="https://wa.me/{{ $whatsapp }}" target="_blank">{{ $data->whatsapp }}</a></td>
                        <td>{{ strtoupper($data->name) }}</td>
                        <td>{{ $data->coordinator->name ?? null }}</td>
                        <td>{{ $data->volunteer_data->name ?? null }}</td>
                        <td>{{ $data->subdistrict->name ?? null }}</td>
                        <td>{{ $data->no_tps }}</td>
                        <td nowrap>
                            @if($data->status == 1)
                                <select class="form-select btn btn-secondary px-2 py-1 f-14px updateStatus" data-url="{{ url($controller_name.'/updateStatus/'.$data->id) }}">
                                    @foreach(status() as $key => $status))
                                        <option value="{{ $key }}" {{ $key == $data->status? 'selected' : '' }}>{{ $status }}</option>
                                    @endforeach
                                </select>
                            @else
                                <a class="btn btn-{{ statusColor()[$data->status] ?? null }} px-2 py-1 f-14px">
                                    {{ status()[$data->status] ?? null }}
                                </a>
                            @endif
                        </td>
                        <td nowrap>
                            @if($groups_id == 1)
                                <a class="btn btn-{{ status_shareColor()[$data->status_share] ?? null }} px-2 py-1 f-14px {{ $data->status != 2? 'disabled' : '' }}" {!! $data->status_share == 1? 'href='.url($controller_name.'/updateStatusShare/'.$data->id) : '' !!}>
                                    {{ status_share()[$data->status_share] ?? null }}
                                </a>
                            @else
                                <a class="btn btn-{{ status_shareColor()[$data->status_share] ?? null }} px-2 py-1 f-14px {{ $data->status != 2? 'disabled' : '' }}">
                                    {{ status_share()[$data->status_share] ?? null }}
                                </a>
                            @endif
                        </td>
                        <td class="action text-center" nowrap>
                            <a class="btn btn-secondary px-2 py-1" href="{{ url($controller_name.'/logActivity/'.$data->id) }}">
                                <i class="fa-solid fa-list"></i>
                            </a>
                            @if($user_id == $data->coordinator_id || $groups_id == 1)
                                <a class="btn btn-primary px-2 py-1" href="{{ url($controller_name.'/edit/'.$data->id) }}">
                                    <i class="fa-solid fa-pencil"></i>
                                </a>
                                <a class="btn btn-danger px-2 py-1 delete" data-name="{{ strtoupper($data->name) }}" href="{{ url($controller_name.'/delete/'.$data->id) }}">
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
            {{ $datas->onEachSide(0)->onEachSide(0)->appends($param)->links('component.pagination')}}        
        </div>
    </div>
</div>

</form>

@section('scripts')
<script type="text/javascript">
    $('.updateStatus').change(function() {
        var url = $(this).data('url'),
            val = $(this).val();
        window.location.href = url+'?status='+val;
    });
</script>
@endsection