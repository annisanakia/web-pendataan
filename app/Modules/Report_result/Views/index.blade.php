@extends('layouts.layout')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Laporan Hasil Pemilu</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Laporan Hasil Pemilu</li>
    </ol>
    <form method="GET" action="{{ url($controller_name).'/getData' }}" accept-charset="UTF-8" class="form-validation">
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="alert alert-warning text-center">
                    <h2>Real Count</h2>
                    <h4 id="countdown">0</h4>
                </div>
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Pilih Model</h5>
                        <!-- General Form Elements -->
                        <div class="row mt-4">
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label class="col-form-label">Model Laporan</label>
                                    <select name="model" class="form-control selectpicker {{ $errors->has('model')? 'is-invalid' : '' }}" data-size="7">
                                        <option value="1">Berdasarkan Kecamatan</option>
                                        <option value="2">Berdasarkan Kelurahan</option>
                                        <option value="3">Berdasarkan TPS</option>
                                    </select>
                                    {!!$errors->first('model', ' <span class="invalid-feedback">:message</span>')!!}
                                </div>
                            </div>
                            <div class="col-sm-6 d-none" id="coordinator_subdistrict">
                                <div class="mb-3">
                                    <label class="col-form-label">Kelurahan</label>
                                    <select name="subdistrict_ids[]" class="form-control selectpicker {{ $errors->has('subdistrict_ids')? 'is-invalid' : '' }}" data-size="7" data-live-search="true" data-actions-box="true" data-selected-text-format="count" title="-- Pilih All --" multiple>
                                        @foreach(\Models\subdistrict::whereIn('id',$subdistrict_ids)->get() as $row)
                                            <option value="{{ $row->id }}">{{ $row->name }}</option>
                                        @endforeach
                                    </select>
                                    {!!$errors->first('subdistrict_ids', ' <span class="invalid-feedback">:message</span>')!!}
                                </div>
                            </div>
                            <div class="col-sm-6 d-none" id="tps_subdistrict">
                                <div class="mb-3">
                                    <label class="col-form-label">Kelurahan</label>
                                    <select name="subdistrict_id" class="form-control selectpicker {{ $errors->has('subdistrict_id')? 'is-invalid' : '' }}" data-size="7" data-live-search="true">
                                        <option value="">-- Pilih --</option>
                                        @foreach(\Models\subdistrict::whereIn('id',$subdistrict_ids)->get() as $row)
                                            <option value="{{ $row->id }}">{{ $row->name }}</option>
                                        @endforeach
                                    </select>
                                    {!!$errors->first('subdistrict_id', ' <span class="invalid-feedback">:message</span>')!!}
                                </div>
                            </div>
                        </div>
                        <div class="d-grid gap-2 d-md-block my-2 text-end">
                            <button type="submit" class="btn btn-primary px-3 btn-submit">
                                Preview
                            </button>
                        </div>
                    </div>
                </div>
                <div id="getData" class="position-relative" style="min-height: 200px;">
                    <div class="alert alert-info text-center mt-4">
                        Pilih model laporan kemudian klik preview untuk melihat laporan
                    </div>
                </div>
            </div>
        </div>
    </section>
    </form>
</div>
@endsection

@section('scripts')
<script src="{{ asset('assets/js/chart.min.js') }}" crossorigin="anonymous"></script>
<link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap-select/1.13.14/css/bootstrap-select.min.css')}}">
<script src="{{ asset('assets/plugins/bootstrap-select/v1.14.0-beta2/bootstrap-select.js')}}"></script>
<script type="text/javascript">
    $('.selectpicker').selectpicker('refresh');
    $("select[name=model]").change(function (e) {
        e.preventDefault();
        var val = $(this).val();
        $('#coordinator_subdistrict').addClass('d-none');
        $('#status').addClass('d-none');
        $('#tps_subdistrict').addClass('d-none');
        if(val == 2){
            $('#coordinator_subdistrict').removeClass('d-none');
        }else if(val == 3){
            $('#tps_subdistrict').removeClass('d-none');
        }
    });
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
    function quickCount() {
        console.log('each 1 second...');
        count = count+1;
        $.ajax({
            url: "{{ url($controller_name.'/quickCount') }}",
            type: 'GET',
            success: function(data) { 
                // $('#getData').html(data);
                $("#countdown").html(data.count);
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
    var count = 0;
    var intervalId = window.setInterval(function(){
            quickCount();
        }, 1000);
</script>
@endsection