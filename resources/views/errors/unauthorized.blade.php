@extends('layouts.app')
@section('content_app')
<main class="bg-softblue">
    <div class="container">
        <section class="section error-404 min-vh-100 d-flex flex-column align-items-center justify-content-center text-center">
            <h1 style="font-size: 70px;">ACCESS DENIED</h1>
            <h2>You have tried to access a page that you have no permission to view.</h2>
            <a class="btn btn-secondary" href="{{ url('/') }}">Back to home</a>
            <div class="credits mt-4 small-text">
                © 2023 Smartrio
            </div>
        </section>
    </div>
</main><!-- End #main -->
@endsection