@extends('layouts.layout')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Data Pendataan</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Data Pendataan</li>
    </ol>
    <section class="section">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-4">Detail Data</h5>
                <!-- General Form Elements -->
                <div class="row">
                    <div class="col-sm-6">
                        <div class="mb-2">
                            <div class="mb-1 fw-semibold">NIK</div>
                            {{ $data->nik ?? null }}
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="mb-2">
                            <div class="mb-1 fw-semibold">Nama Lengkap</div>
                            {{ strtoupper($data->name ?? null) }}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="mb-2">
                            <div class="mb-1 fw-semibold">Wilayah</div>
                            {{ $data->city->name ?? null }}
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="mb-2">
                            <div class="mb-1 fw-semibold">Kecamatan</div>
                            {{ $data->district->name ?? null }}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="mb-2">
                            <div class="mb-1 fw-semibold">Kelurahan</div>
                            {{ $data->subdistrict->name ?? null }}
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="mb-2">
                            <div class="mb-1 fw-semibold">Nomor TPS</div>
                            {{ $data->no_tps ?? null }}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="mb-2">
                            <div class="mb-1 fw-semibold">Foto KTP</div>
                            @if($data->photo != '')
                                <img class="object-fit-cover" style="height: 150px;" src="{{ asset($data->photo) }}">
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="mb-2">
                            <div class="mb-1 fw-semibold">Nomor Whatsapp</div>
                            {{ $data->whatsapp ?? null }}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="mb-2">
                            <div class="mb-1 fw-semibold">Tempat, Tanggal Lahir</div>
                            {{ $data->pob ?? '-' }}, {{ dateToIndo($data->dob ?? null) }}
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="mb-2">
                            <div class="mb-1 fw-semibold">Jenis Kelamin</div>
                            {{ ($data->gender ?? null) == 'L'? 'Laki-laki' : 'Perempuan' }}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="mb-2">
                            <div class="mb-1 fw-semibold">Agama</div>
                            {{ $data->religion->name ?? null }}
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="mb-2">
                            <div class="mb-1 fw-semibold">Pekerjaan</div>
                            {{ $data->job_name ?? null }}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="mb-2">
                            <div class="mb-1 fw-semibold">Alamat</div>
                            {{ $data->address ?? null }}
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="mb-2">
                            <div class="mb-1 fw-semibold">RT / RW</div>
                            {{ $data->rt ?? '-' }} / {{ $data->rw ?? '-' }}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="mb-2">
                            <div class="mb-1 fw-semibold">Koordinator</div>
                            {{ $data->coordinator->name ?? null }}
                        </div>
                    </div>
                </div>
                <form method="GET" action="{{ url($controller_name).'/logActivity/'.$data->id }}" accept-charset="UTF-8">
                <div class="select-max-row d-inline-block mt-2">
                    Show 
                    <input type="text" name="max_row" value="{{ $log_activitys->perPage() }}" size="4" maxlength="4" class="text-center"> entries
                </div>
                <div class="table-responsive">
                <table class="table table-striped mt-3">
                    <thead>
                        <tr>
                            <th width="5%" class="text-center">No</th>
                            <th>Koordinator</th>
                            <th>Aktivitas</th>
                            <th>Tanggal</th>
                        </tr>
                        <tr>
                            <th><button type="submit" class="btn"><i class="fas fa-search"></i></span></button></th>
                            <th><input type="text" name="filter[user_name]" value="{{ $param['filter']['user_name'] ?? null }}" class="form-control"></th>
                            <th><input type="text" name="filter[activity]" value="{{ $param['filter']['activity'] ?? null }}" class="form-control"></th>
                            <th><input type="datetime-local" name="filter[datetime]" value="{{ $param['filter']['datetime'] ?? null }}" class="form-control"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($log_activitys) <= 0)
                            <tr>
                                <td colspan="8" class="text-center">Data Tidak Ditemukan</td>
                            </tr>
                        @else
                            @php $i=0 @endphp
                            @foreach($log_activitys as $log_activity)
                            <tr>
                                <td class="text-center">{{ (($log_activitys->currentPage() - 1 ) * $log_activitys->perPage() ) + ++$i }}</td>
                                <td>{{ $log_activity->user->name ?? null }}</td>
                                <td>{{ $log_activity->activity ?? null }}</td>
                                <td>{{ dateToIndo($log_activity->activity_date ?? null) }} {{ date('H:i',strtotime($log_activity->activity_date ?? null)) }}</td>
                            </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
                </div>
                <!-- End Table with stripped rows -->
                <div class="table-list-footer">
                    <span class="result-count">Showing {{$log_activitys->firstItem()}} to {{$log_activitys->lastItem()}} of {{$log_activitys->total()}} entries</span>
                    {{ $log_activitys->appends($param)->links('component.pagination')}}        
                </div>
                </form>
            </div>
        </div>
    </section>
</div>
@endsection