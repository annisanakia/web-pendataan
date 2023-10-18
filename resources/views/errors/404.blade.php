@extends('layouts.app')
@section('content_app')
<main class="bg-softblue">
    <div class="container">
        <section class="section error-404 min-vh-100 d-flex flex-column align-items-center justify-content-center text-center">
            <h1>404</h1>
            <h2>The page you are looking for doesn't exist.</h2>
            <a class="btn btn-secondary" href="{{ url('/') }}">Back to home</a>
            <div class="credits mt-4 small-text">
                © 2023 Smartrio
            </div>
        </section>
    </div>
</main><!-- End #main -->
@endsection