@extends('layouts.layout')
@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Dashboard</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Dashboard</li>
    </ol>
    <div class="row">
        <?php
            $colors = ['#4e88d0', '#64b672', '#c05252', '#dd793c', '#52c8c1'];
        ?>
        @foreach($districts as $key => $district)
        <?php
            $key = $key > 5? $key-5 : $key;
            $total = $collection_datas->where('district_id',$district->id)->count();
        ?>
        <div class="col-md">
            <div class="card bg-primary text-white mb-4 text-center" style="background-color: {{ $colors[$key] ?? '#4e88d0' }} !important;">
                <div class="card-header"><b>{{ $district->name }}</b></div>
                <div class="card-body">{{ $total }} Data</div>
            </div>
        </div>
        @endforeach
    </div>
    <div class="row mb-4">
        <div class="col-md-6">
            <label class="form-label">Kecamatan</label>
            <select class="form-select" name="district_id">
                @foreach($districts as $key => $row)
                    <option value="{{ $row->id }}" {{ $key == 0? 'selected' : '' }}>{{ $row->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div id="getData">

    </div>
</div>
@endsection

@section('scripts')
<script type="text/javascript">
    getData($('select[name=district_id]').val());
    function getData(district_id){
        $.ajax({
            url: "{{ url($controller_name.'/getData') }}",
            type: 'GET',
            data: {district_id : district_id},
            success: function(data) { 
                $('#getData').html(data);
            }
        });
    }

    $('select[name=district_id]').change(function() {
        getData($(this).val());
    });
</script>
@endsection