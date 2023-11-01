<form method="GET" action="{{ url($controller_name) }}" accept-charset="UTF-8">

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
                    <th width="5%" class="text-center">No</th>
                    <th>NIK</th>
                    <th width="20%">Nama Lengkap</th>
                    <th width="15%">Koordinator</th>
                    <th width="15%">Kelurahan</th>
                    <th width="7%">TPS</th>
                    <th width="13%">Status</th>
                    <th width="13%">Status<br>Dibagikan</th>
                    <th width="10%" class="text-center">Aksi</th>
                </tr>
                <tr>
                    <th><button type="submit" class="btn"><i class="fas fa-search"></i></span></button></th>
                    <th><input type="text" name="filter[nik]" value="{{ $param['filter']['nik'] ?? null }}" class="form-control"></th>
                    <th><input type="text" name="filter[name]" value="{{ $param['filter']['name'] ?? null }}" class="form-control"></th>
                    <th><input type="text" name="filter[coordinator_name]" value="{{ $param['filter']['coordinator_name'] ?? null }}" class="form-control"></th>
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
                        <td colspan="9" class="text-center">Data Tidak Ditemukan</td>
                    </tr>
                @else
                    @php $i=0 @endphp
                    @foreach($datas as $data)
                    <tr>
                        <td class="text-center">{{ (($datas->currentPage() - 1 ) * $datas->perPage() ) + ++$i }}</td>
                        <td>{{ $data->nik }}</td>
                        <td>{{ strtoupper($data->name) }}</td>
                        <td>{{ $data->coordinator->name ?? null }}</td>
                        <td>{{ $data->subdistrict->name ?? null }}</td>
                        <td>{{ $data->no_tps }}</td>
                        <td nowrap>
                            @if($groups_id == 1)
                                @switch($data->status)
                                    @case(2)
                                        <a class="btn btn-primary px-2 py-1 f-14px" href="{{ url($controller_name.'/updateStatus/'.$data->id) }}" style="font-si">
                                            Sudah diverifikasi
                                        </a>
                                        @break
                                    @default
                                        <a class="btn btn-secondary px-2 py-1 f-14px" href="{{ url($controller_name.'/updateStatus/'.$data->id) }}" style="font-si">
                                            Belum diverifikasi
                                        </a>
                                @endswitch
                            @else
                                {!! $data->status == 2? '<span class="bg-primary text-white px-2 py-1 f-14px">Sudah diverifikasi</span>' : '<span class="bg-secondary text-white px-2 py-1 f-14px">Belum diverifikasi</span>' !!}
                            @endif
                        </td>
                        <td nowrap>
                            @if($groups_id == 1)
                                @switch($data->status_share)
                                    @case(2)
                                        <a class="btn btn-success px-2 py-1 f-14px" href="{{ url($controller_name.'/updateStatusShare/'.$data->id) }}" style="font-si">
                                            Sudah dibagikan
                                        </a>
                                        @break
                                    @default
                                        <a class="btn btn-secondary px-2 py-1 f-14px {{ $data->status != 2? 'disabled' : '' }}" href="{{ url($controller_name.'/updateStatusShare/'.$data->id) }}" style="font-si">
                                            Belum dibagikan
                                        </a>
                                @endswitch
                            @else
                                {!! $data->status_share == 2? '<span class="bg-success text-white px-2 py-1 f-14px">Sudah dibagikan</span>' : '<span class="bg-secondary text-white px-2 py-1 f-14px">Belum dibagikan</span>' !!}
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
            {{ $datas->appends($param)->links('component.pagination')}}        
        </div>
    </div>
</div>

</form>