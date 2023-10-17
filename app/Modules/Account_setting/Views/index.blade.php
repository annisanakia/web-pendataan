@extends('layouts.layout')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Account Setting</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Account Setting</li>
    </ol>

    <section class="section profile">
        <div class="row">
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
                        <div class="rounded-circle border d-flex align-items-center justify-content-center fs-1" style="width: 130px;height: 130px;">
                            <i class="fa-solid fa-user" alt="Profile"></i>
                        </div>
                        <h2>{{ $data->name }}</h2>
                        <h3>{{ $data->group->name ?? null }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-xl-8">

                <div class="card">
                    <div class="card-body pt-3">
                        <!-- Bordered Tabs -->
                        <ul class="nav nav-tabs nav-tabs-bordered">
                            <li class="nav-item">
                                <button class="nav-link {{ !in_array(Session::get('type'),[1,2])? 'active' : ''  }}" data-bs-toggle="tab" data-bs-target="#profile-overview">Informasi Profil</button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link {{ Session::get('type') == 1? 'active' : ''  }}" data-bs-toggle="tab" data-bs-target="#profile-edit">Ubah Profil</button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link {{ Session::get('type') == 2? 'active' : ''  }}" data-bs-toggle="tab" data-bs-target="#profile-change-password">Ubah Password</button>
                            </li>
                        </ul>
                        <div class="tab-content pt-2">
                            <div class="tab-pane fade {{ !in_array(Session::get('type'),[1,2])? 'show active' : ''  }} profile-overview" id="profile-overview">
                                <h5 class="card-title mt-2">Detail Profil</h5>
                                <div class="row my-2">
                                    <div class="col-lg-3 col-md-4 label">Nama</div>
                                    <div class="col-lg-9 col-md-8">{{ $data->name }}</div>
                                </div>
                                <div class="row my-2">
                                    <div class="col-lg-3 col-md-4 label">Username</div>
                                    <div class="col-lg-9 col-md-8">{{ $data->username }}</div>
                                </div>
                                <div class="row my-2">
                                    <div class="col-lg-3 col-md-4 label">Email</div>
                                    <div class="col-lg-9 col-md-8">{{ $data->email }}</div>
                                </div>
                                <div class="row my-2">
                                    <div class="col-lg-3 col-md-4 label">Nomor Telepon</div>
                                    <div class="col-lg-9 col-md-8">{{ $data->phone_no }}</div>
                                </div>
                            </div>
                            <div class="tab-pane fade {{ Session::get('type') == 1? 'show active' : ''  }} profile-edit pt-3" id="profile-edit">
                                <!-- Profile Edit Form -->
                                <form method="POST" action="{{ route($controller_name.'.update',$data->id) }}" class="form-validation update" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="col-form-label">Name</label>
                                                <input name="name" type="text" class="form-control {{ $errors->has('name')? 'is-invalid' : '' }}" value="{{ old('name') ?? $data->name }}">
                                                {!!$errors->first('name', ' <span class="invalid-feedback">:message</span>')!!}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="col-form-label">Username</label>
                                                <input name="username" type="text" class="form-control {{ $errors->has('username')? 'is-invalid' : '' }}" value="{{ old('username') ?? $data->username }}" disabled>
                                                {!!$errors->first('username', ' <span class="invalid-feedback">:message</span>')!!}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="col-form-label">Email</label>
                                                <input name="email" type="text" class="form-control {{ $errors->has('email')? 'is-invalid' : '' }}" value="{{ old('email') ?? $data->email }}">
                                                {!!$errors->first('email', ' <span class="invalid-feedback">:message</span>')!!}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="col-form-label">Nomor Telepon</label>
                                                <input name="phone_no" type="text" class="form-control {{ $errors->has('phone_no')? 'is-invalid' : '' }}" value="{{ old('phone_no') ?? $data->phone_no }}">
                                                {!!$errors->first('phone_no', ' <span class="invalid-feedback">:message</span>')!!}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-grid gap-2 d-md-block my-2 text-end">
                                        <button type="submit" class="btn btn-success px-3 btn-submit">
                                            Simpan Perubahan
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <div class="tab-pane fade {{ Session::get('type') == 2? 'show active' : ''  }} profile-edit pt-3" id="profile-change-password">
                                <!-- Change Password Form -->
                                <form method="POST" action="{{ route($controller_name.'.update_password',$data->id) }}" class="form-validation update_password" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="col-form-label">Password Saat Ini</label>
                                                <input name="current_password" type="password" class="form-control {{ $errors->has('current_password')? 'is-invalid' : '' }}" value="{{ old('current_password') }}">
                                                {!!$errors->first('current_password', ' <span class="invalid-feedback">:message</span>')!!}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="col-form-label">Username</label>
                                                <input name="username" type="text" class="form-control {{ $errors->has('username')? 'is-invalid' : '' }}" value="{{ old('username') ?? $data->username }}" disabled>
                                                {!!$errors->first('username', ' <span class="invalid-feedback">:message</span>')!!}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="col-form-label">Password Baru</label>
                                                <input name="password" type="password" class="form-control {{ $errors->has('password')? 'is-invalid' : '' }}" value="{{ old('password') }}">
                                                {!!$errors->first('password', ' <span class="invalid-feedback">:message</span>')!!}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="col-form-label">Konfirmasi Password Baru</label>
                                                <input name="password_confirmation" type="password" class="form-control {{ $errors->has('password_confirmation')? 'is-invalid' : '' }}" value="{{ old('password_confirmation') }}">
                                                {!!$errors->first('password_confirmation', ' <span class="invalid-feedback">:message</span>')!!}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-grid gap-2 d-md-block my-2 text-end">
                                        <button type="submit" class="btn btn-success px-3 btn-submit">
                                            Simpan Password
                                        </button>
                                    </div>
                                </form><!-- End Change Password Form -->
                            </div>
                        </div><!-- End Bordered Tabs -->
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@section('scripts')
<script type="text/javascript">
    @if(Session::get('success') == 1)
        swalSaveButtons.fire('Simpan berhasil!', '', 'success')
    @endif
    $(".btn-submit").click(function (e) {
        e.preventDefault();

        e.stopPropagation();
        e.stopImmediatePropagation();

        swalSaveButtons.fire({
            title: 'Simpan perubahan?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Simpan',
            cancelButtonText: 'Batal',
            reverseButtons: true
            }).then((result) => {
            if (result.isConfirmed) {
                $(this).closest("form").submit();
            } else if (
                /* Read more about handling dismissals below */
                result.dismiss === Swal.DismissReason.cancel
            ) {}
        });
    });
</script>
@endsection