@extends('layouts.admin')

@section('title', 'Tambah Mahasiswa')

@section('content')
<div class="mb-4">
    <h2><i class="bi bi-people"></i> Tambah Mahasiswa</h2>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('mahasiswa.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="nim" class="form-label">NIM <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('nim') is-invalid @enderror" id="nim" name="nim" value="{{ old('nim') }}" required>
                    @error('nim')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="nama" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" value="{{ old('nama') }}" required>
                    @error('nama')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="jurusan" class="form-label">Jurusan <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('jurusan') is-invalid @enderror" id="jurusan" name="jurusan" value="{{ old('jurusan') }}" required>
                    @error('jurusan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="semester" class="form-label">Semester <span class="text-danger">*</span></label>
                    <select class="form-select @error('semester') is-invalid @enderror" id="semester" name="semester" required>
                        <option value="">Pilih Semester</option>
                        @for($i = 1; $i <= 14; $i++)
                            <option value="{{ $i }}" {{ old('semester') == $i ? 'selected' : '' }}>Semester {{ $i }}</option>
                        @endfor
                    </select>
                    @error('semester')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="no_hp" class="form-label">No HP</label>
                    <input type="text" class="form-control @error('no_hp') is-invalid @enderror" id="no_hp" name="no_hp" value="{{ old('no_hp') }}">
                    @error('no_hp')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="mt-3">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Simpan
                </button>
                <a href="{{ route('mahasiswa.index') }}" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i> Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
