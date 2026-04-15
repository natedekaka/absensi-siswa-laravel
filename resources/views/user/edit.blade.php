@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Edit User</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('user.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" class="form-control" value="{{ $user->username }}" disabled>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password Baru (kosongkan jika tidak diubah)</label>
                        <input type="password" name="password" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="nama" class="form-control" value="{{ old('nama', $user->nama) }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <select name="role" class="form-select" required>
                            <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="guru" {{ $user->role == 'guru' ? 'selected' : '' }}>Guru</option>
                        </select>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Update</button>
                        <a href="{{ route('user.index') }}" class="btn btn-secondary">Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
