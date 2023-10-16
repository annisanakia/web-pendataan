@extends('layouts.app')
@section('content_app')
<main class="bg-softblue">
    <div class="container">
        <section class="section login min-vh-100 d-flex flex-column align-items-center justify-content-center py-4 pt-0">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">
                        <div class="d-flex justify-content-center py-4 text-primary fs-1 fw-bold">
                            Smartrio
                        </div>
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="pt-4 pb-2">
                                    <h5 class="card-title text-center pb-0 fs-4">Login</h5>
                                </div>
                                <form class="row g-3 needs-validation" method="POST" action="{{ route('login') }}">
                                    @csrf
                                    <div class="col-12 {{ $errors->has('username') ? ' is-invalid' : '' }}">
                                        <label for="yourUsername" class="form-label">Username</label>
                                        <div class="input-group has-validation">
                                            <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
                                            <input type="text" name="username" class="form-control" id="yourUsername">
                                            <div class="invalid-feedback">{{ $errors->first('username') }}</div>
                                        </div>
                                    </div>
                                    <div class="col-12 {{ $errors->has('username') ? ' is-invalid' : '' }}">
                                        <label for="yourPassword" class="form-label">Password</label>
                                        <div class="input-group has-validation">
                                            <span class="input-group-text"><i class="fa-solid fa-key"></i></span>
                                            <input type="password" name="password" class="form-control" id="yourPassword">
                                            <div class="invalid-feedback">{{ $errors->first('password') }}</div>
                                        </div>
                                    </div>
                                    @if($errors->has('user_valid'))
                                    <div class="col-12">
                                        <div class="alert alert-danger" role="alert">
                                            {{ $errors->first('user_valid') }}
                                        </div>
                                    </div>
                                    @endif
                                    <div class="col-12 my-4">
                                        <button class="btn btn-primary w-100" type="submit">Login</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="credits small-text">
                            &copy; Copyright 2023 <strong>Smartrio</strong>. All Rights Reserved
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</main><!-- End #main -->
@endsection