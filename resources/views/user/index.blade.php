@extends('layouts.app')

@section('title', 'Manajemen User')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Manajemen User</h4>
    <a href="{{ route('user.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i> Tambah User
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Nama</th>
                        <th>Role</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td>{{ $user->username }}</td>
                        <td>{{ $user->nama }}</td>
                        <td>
                            @if($user->role == 'admin')
                                <span class="badge bg-primary">Admin</span>
                            @else
                                <span class="badge bg-secondary">Guru</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('user.edit', $user->id) }}" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                            @if(auth()->id() !== $user->id)
                            <form action="{{ route('user.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">Belum ada user</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
