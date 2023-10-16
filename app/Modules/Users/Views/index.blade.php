@extends('layouts.layout')

@section('content')
<div class="pagetitle">
    <h1>Daftar Pengguna</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
            <li class="breadcrumb-item active">Daftar Pengguna</li>
        </ol>
    </nav>
</div>

<section class="section">
    @include(ucwords($controller_name).'::list')
</section>
@endsection