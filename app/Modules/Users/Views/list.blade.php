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
        <div class="table-responsive">
        <table class="table table-striped mt-3">
            <thead>
                <tr>
                    <th width="5%" class="text-center">No</th>
                    <th>Kode</th>
                    <th>Detail Pengguna</th>
                    <th width="28%">Grup pengguna</th>
                    <th width="24%">Status</th>
                    <th width="12%" class="text-center">Aksi</th>
                </tr>
                <tr>
                    <th><button type="submit" class="btn"><i class="fas fa-search"></i></span></button></th>
                    <th><input type="text" name="filter[code]" value="{{ $param['filter']['code'] ?? null }}" class="form-control"></th>
                    <th><input type="text" name="filter[name]" value="{{ $param['filter']['name'] ?? null }}" class="form-control"></th>
                    <th>
                        <select name="filter[groups_id]" class="form-select">
                            <option value="" selected>-- Pilih --</option>
                            @foreach(\Models\groups::all() as $row)
                                <option value="{{ $row->id }}" {{ $row->id == ($param['filter']['groups_id'] ?? null)? 'selected' : '' }}>{{ $row->name }}</option>
                            @endforeach
                        </select>
                    </th>
                    <th>
                        <select name="filter[status]" class="form-select">
                            <option value="" selected>-- Pilih --</option>
                            <option value="1" {{ 1 == ($param['filter']['status'] ?? null)? 'selected' : '' }}>Aktif</option>
                            <option value="2" {{ 2 == ($param['filter']['status'] ?? null)? 'selected' : '' }}>Tidak Aktif</option>
                        </select>
                    </th>
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
                        <td>{{ $data->code }}</td>
                        <td>{{ $data->name }}</td>
                        <td>{{ $data->group->name ?? null }}</td>
                        <td>{{ $data->status == 1? 'Aktif' : 'Tidak Aktif' }}</td>
                        <td class="action text-center" nowrap>
                            <a class="btn btn-primary px-2 py-1" href="{{ url($controller_name.'/edit/'.$data->id) }}">
                                <i class="fa-solid fa-pencil"></i>
                            </a>
                            <a class="btn btn-danger px-2 py-1 delete" data-name="{{ $data->name }}" href="{{ url($controller_name.'/delete/'.$data->id) }}">
                                <i class="fa-solid fa-trash"></i>
                            </a>
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