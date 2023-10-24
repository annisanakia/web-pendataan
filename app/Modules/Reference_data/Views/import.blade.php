@extends('layouts.layout')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Data Referensi</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Data Referensi</li>
    </ol>
    <section class="section">

        <div class="card">
            <div class="card-body">
            <h5 class="card-title">Import Data</h5>
            <!-- General Form Elements -->
            <div class="alert alert-info mt-3">
                <b>Mohon diperhatikan :</b>
                <ul class="mb-1">
                    <li>Download dan isi template excel yang telah disediakan</li>
                    <li>Format File harus sesuai dengan template yg didownload</li>
                    <li>Upload file template excel yang telah diisi</li>
                    <li>Harap dicheck kembali data yang ingin disimpan</li>
                </ul>
            </div>
            <a href="{{ url($controller_name.'/getTemplateAsXls') }}" target="_blank" class="btn btn-primary align-middle py-2">
                <i class="fa-solid fa-download me-2"></i>Download Template
            </a>
            <div class="row mt-2">
                <div class="col-sm-6">
                    <label class="col-form-label">Upload template</label>
                    <input name="photo" class="form-control {{ $errors->has('photo')? 'is-invalid' : '' }}" type="file">
                    <div class="form-text">Upload file berformat XLS.</div>
                    {!!$errors->first('photo', ' <span class="invalid-feedback">:message</span>')!!}
                </div>
            </div>
        </div>
    </section>
</div>
@endsection