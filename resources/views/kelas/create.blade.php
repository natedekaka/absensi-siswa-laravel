<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Kelas - Absensi Siswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root { --primary: #4f46e5; --green: #25C185; }
        body { background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); min-height: 100vh; }
        .form-page { min-height: calc(100vh - 100px); display: flex; align-items: center; justify-content: center; padding: 20px 0; }
        .form-card { border: none; border-radius: 20px; overflow: hidden; box-shadow: 0 10px 40px rgba(0,0,0,0.1); max-width: 500px; width: 100%; }
        .form-card-header { background: linear-gradient(135deg, var(--primary) 0%, #6c5ce7 100%); padding: 2rem; text-align: center; }
        .form-card-header h3 { color: white; font-weight: 600; margin: 0; }
        .form-card-header .icon-circle { width: 70px; height: 70px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; }
        .form-card-header .icon-circle i { font-size: 1.75rem; color: white; }
        .form-card-body { padding: 2rem; }
        .form-control, .form-select { border: 2px solid #e0e0e0; border-radius: 12px; padding: 0.75rem 1rem; transition: all 0.3s; }
        .form-control:focus, .form-select:focus { border-color: var(--green); box-shadow: 0 0 0 4px rgba(37,211,102,0.15); }
        .btn-back { border: 2px solid #e0e0e0; border-radius: 12px; padding: 0.75rem 1.5rem; color: #666; transition: all 0.3s; }
        .btn-back:hover { background: #f8f9fa; border-color: #ccc; }
        .back-btn { position: fixed; top: 20px; left: 20px; width: 45px; height: 45px; border-radius: 14px; background: rgba(255,255,255,0.9); border: none; display: flex; align-items: center; justify-content: center; color: var(--primary); font-size: 18px; cursor: pointer; box-shadow: 0 4px 15px rgba(0,0,0,0.1); z-index: 100; text-decoration: none; }
        .back-btn:hover { transform: scale(1.1); background: white; }
    </style>
</head>
<body>
    <a href="{{ route('kelas.index') }}" class="back-btn"><i class="fas fa-arrow-left"></i></a>

    <div class="form-page">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-5">
                    <div class="form-card">
                        <div class="form-card-header">
                            <div class="icon-circle">
                                <i class="fas fa-door-open"></i>
                            </div>
                            <h3>Tambah Kelas Baru</h3>
                            <p class="mb-0 opacity-75">Isi informasi kelas dengan lengkap</p>
                        </div>
                        <div class="form-card-body">
                            @if($errors->any())
                                <div class="alert alert-danger bg-danger text-white border-0 rounded-3 mb-4">
                                    <i class="fas fa-exclamation-circle me-2"></i>{{ $errors->first() }}
                                </div>
                            @endif

                            <form method="POST">
                                @csrf
                                <div class="mb-4">
                                    <label class="form-label fw-semibold text-dark">Nama Kelas</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-0">
                                            <i class="fas fa-door-closed text-muted"></i>
                                        </span>
                                        <input type="text" name="nama_kelas" class="form-control" 
                                               placeholder="Contoh: X IPA 1" value="{{ old('nama_kelas') }}" required>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label fw-semibold text-dark">Wali Kelas</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-0">
                                            <i class="fas fa-user-tie text-muted"></i>
                                        </span>
                                        <input type="text" name="wali_kelas" class="form-control" 
                                               placeholder="Nama lengkap wali kelas" value="{{ old('wali_kelas') }}">
                                    </div>
                                </div>
                                <div class="d-flex gap-3">
                                    <a href="{{ route('kelas.index') }}" class="btn btn-back flex-fill">
                                        <i class="fas fa-arrow-left me-2"></i>Batal
                                    </a>
                                    <button type="submit" class="btn btn-primary flex-fill">
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
</body>
</html>
