@extends('layouts.layout')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Daftar Kelurahan</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Daftar Kelurahan</li>
    </ol>
    <section class="section">
        @include(ucwords($controller_name).'::list')
    </section>
</div>
@endsection