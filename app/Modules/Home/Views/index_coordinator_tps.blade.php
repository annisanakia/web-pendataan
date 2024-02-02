@extends('layouts.layout')
@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Dashboard</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Dashboard</li>
    </ol>
    <div class="row">
        <div class="col-lg-12">
            <div class="alert alert-warning text-center">
                <h2>Real Count</h2>
                <h4 id="countdown">0</h4>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    function quickCount() {
        console.log('each 1 second...');
        count = count+1;
        $.ajax({
            url: "{{ url('report_result/quickCount') }}",
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
