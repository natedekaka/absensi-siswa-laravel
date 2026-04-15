<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Siswa - Absensi Siswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root { --primary: #4f46e5; --green: #25C185; --warning: #ffb142; }
        body { background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); min-height: 100vh; }
        .form-page { min-height: calc(100vh - 100px); display: flex; align-items: center; justify-content: center; padding: 20px 0; }
        .form-card { border: none; border-radius: 20px; overflow: hidden; box-shadow: 0 10px 40px rgba(0,0,0,0.1); max-width: 550px; width: 100%; }
        .form-card-header { background: linear-gradient(135deg, var(--warning) 0%, #ff8c00 100%); padding: 2rem; text-align: center; }
        .form-card-header h3 { color: white; font-weight: 600; margin: 0; }
        .form-card-header .icon-circle { width: 70px; height: 70px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; }
        .form-card-header .icon-circle i { font-size: 1.75rem; color: white; }
        .form-card-body { padding: 2rem; }
        .form-control, .form-select { border: 2px solid #e0e0e0; border-radius: 12px; padding: 0.75rem 1rem; transition: all 0.3s; }
        .form-control:focus, .form-select:focus { border-color: var(--green); box-shadow: 0 0 0 4px rgba(37,211,102,0.15); }
        .btn-back { border: 2px solid #e0e0e0; border-radius: 12px; padding: 0.75rem 1.5rem; color: #666; transition: all 0.3s; }
        .btn-back:hover { background: #f8f9fa; border-color: #ccc; }
        .gender-option { border: 2px solid #e0e0e0; border-radius: 12px; padding: 1rem; cursor: pointer; transition: all 0.3s; text-align: center; }
        .gender-option:hover { border-color: var(--green); }
        .gender-option.selected { border-color: var(--green); background: rgba(37,211,102,0.1); }
        .gender-option i { font-size: 1.5rem; margin-bottom: 0.5rem; }
        .back-btn { position: fixed; top: 20px; left: 20px; width: 45px; height: 45px; border-radius: 14px; background: rgba(255,255,255,0.9); border: none; display: flex; align-items: center; justify-content: center; color: var(--primary); font-size: 18px; cursor: pointer; box-shadow: 0 4px 15px rgba(0,0,0,0.1); z-index: 100; text-decoration: none; }
        .back-btn:hover { transform: scale(1.1); background: white; }
    </style>
</head>
<body>
    <a href="{{ route('siswa.index') }}" class="back-btn"><i class="fas fa-arrow-left"></i></a>

    <div class="form-page">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6">
                    <div class="form-card">
                        <div class="form-card-header">
                            <div class="icon-circle">
                                <i class="fas fa-user-edit"></i>
                            </div>
                            <h3>Edit Siswa</h3>
                            <p class="mb-0 opacity-75">Perbarui data siswa</p>
                        </div>
                        <div class="form-card-body">
                            @if($errors->any())
                                <div class="alert alert-danger bg-danger text-white border-0 rounded-3 mb-4">
                                    <i class="fas fa-exclamation-circle me-2"></i>{{ $errors->first() }}
                                </div>
                            @endif

                            <form method="POST">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="id" value="{{ $siswa->id }}">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold text-dark">NIS</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-0">
                                                <i class="fas fa-id-card text-muted"></i>
                                            </span>
                                            <input type="text" name="nis" class="form-control" value="{{ $siswa->nis }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold text-dark">NISN</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-0">
                                                <i class="fas fa-id-card text-muted"></i>
                                            </span>
                                            <input type="text" name="nisn" class="form-control" value="{{ $siswa->nisn }}" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold text-dark">Nama Lengkap</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-0">
                                            <i class="fas fa-user text-muted"></i>
                                        </span>
                                        <input type="text" name="nama" class="form-control" value="{{ $siswa->nama }}" required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold text-dark">Jenis Kelamin</label>
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <label class="gender-option d-block @if($siswa->jenis_kelamin == 'Laki-laki') selected @endif" id="gender-laki">
                                                <input type="radio" name="jenis_kelamin" value="Laki-laki" class="d-none" @if($siswa->jenis_kelamin == 'Laki-laki') checked @endif required>
                                                <i class="fas fa-male d-block text-primary"></i>
                                                <span class="small">Laki-laki</span>
                                            </label>
                                        </div>
                                        <div class="col-6">
                                            <label class="gender-option d-block @if($siswa->jenis_kelamin == 'Perempuan') selected @endif" id="gender-perempuan">
                                                <input type="radio" name="jenis_kelamin" value="Perempuan" class="d-none" @if($siswa->jenis_kelamin == 'Perempuan') checked @endif>
                                                <i class="fas fa-female d-block text-danger"></i>
                                                <span class="small">Perempuan</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label fw-semibold text-dark">Kelas</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-0">
                                            <i class="fas fa-door-open text-muted"></i>
                                        </span>
                                        <select name="kelas_id" class="form-select" required>
                                            <option value="">-- Pilih Kelas --</option>
                                            @foreach($kelas as $k)
                                                <option value="{{ $k->id }}" {{ $siswa->kelas_id == $k->id ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="d-flex gap-3">
                                    <a href="{{ route('siswa.index') }}" class="btn btn-back flex-fill">
                                        <i class="fas fa-arrow-left me-2"></i>Batal
                                    </a>
                                    <button type="submit" class="btn btn-warning text-dark flex-fill">
                                        <i class="fas fa-save me-2"></i>Simpan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.querySelectorAll('.gender-option').forEach(option => {
            option.addEventListener('click', function() {
                document.querySelectorAll('.gender-option').forEach(o => o.classList.remove('selected'));
                this.classList.add('selected');
                this.querySelector('input').checked = true;
            });
        });
    </script>
</body>
</html>
