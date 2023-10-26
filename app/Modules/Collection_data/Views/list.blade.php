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
            $user_id = Auth::user()->id ?? null;
            $groups_id = Auth::user()->groups_id ?? null;
        ?>
        <div class="table-responsive">
        <table class="table table-striped mt-3">
            <thead>
                <tr>
                    <th width="5%" class="text-center">No</th>
                    <th>NIK</th>
                    <th width="20%">Nama</th>
                    <th width="18%">Koordinator</th>
                    <th width="18%">Kelurahan</th>
                    <th width="10%">TPS</th>
                    <th width="10%" class="text-center">Aksi</th>
                </tr>
                <tr>
                    <th><button type="submit" class="btn"><i class="fas fa-search"></i></span></button></th>
                    <th><input type="text" name="filter[nik]" value="{{ $param['filter']['nik'] ?? null }}" class="form-control"></th>
                    <th><input type="text" name="filter[name]" value="{{ $param['filter']['name'] ?? null }}" class="form-control"></th>
                    <th><input type="text" name="filter[coordinator_name]" value="{{ $param['filter']['coordinator_name'] ?? null }}" class="form-control"></th>
                    <th><input type="text" name="filter[subdistrict_name]" value="{{ $param['filter']['subdistrict_name'] ?? null }}" class="form-control"></th>
                    <th><input type="text" name="filter[no_tps]" value="{{ $param['filter']['no_tps'] ?? null }}" class="form-control"></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @if(count($datas) <= 0)
                    <tr>
                        <td colspan="7" class="text-center">Data Tidak Ditemukan</td>
                    </tr>
                @else
                    @php $i=0 @endphp
                    @foreach($datas as $data)
                    <tr>
                        <td class="text-center">{{ (($datas->currentPage() - 1 ) * $datas->perPage() ) + ++$i }}</td>
                        <td>{{ $data->nik }}</td>
                        <td>{{ $data->name }}</td>
                        <td>{{ $data->coordinator->name ?? null }}</td>
                        <td>{{ $data->subdistrict->name ?? null }}</td>
                        <td>{{ $data->no_tps }}</td>
                        <td class="action text-center" nowrap>
                            @if($user_id == $data->coordinator_id || $groups_id == 1)
                            <a class="btn btn-primary px-2 py-1" href="{{ url($controller_name.'/edit/'.$data->id) }}">
                                <i class="fa-solid fa-pencil"></i>
                            </a>
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
            {{ $datas->appends($param)->links('component.pagination')}}        
        </div>
    </div>
</div>

</form>