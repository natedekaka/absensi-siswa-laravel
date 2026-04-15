<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Import Kelas - Absensi Siswa</title>
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
        .template-info { background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%); border-radius: 12px; border-left: 4px solid var(--green); padding: 1rem; margin-top: 1rem; }
        .template-icon { width: 40px; height: 40px; background: var(--green); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: white; }
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
                                <i class="fas fa-file-import"></i>
                            </div>
                            <h3>Import Data Kelas</h3>
                            <p class="mb-0 opacity-75">Upload file CSV untuk import data</p>
                        </div>
                        <div class="form-card-body">
                            <form method="POST" enctype="multipart/form-data" action="{{ route('kelas.import.process') }}">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label fw-semibold text-dark">Pilih File CSV</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-0">
                                            <i class="fas fa-file-csv text-muted"></i>
                                        </span>
                                        <input type="file" name="file" class="form-control" accept=".csv" required>
                                    </div>
                                    <small class="text-muted d-block mt-2">
                                        Format: nama_kelas,wali_kelas
                                    </small>
                                </div>

                                <div class="template-info">
                                    <div class="d-flex align-items-start">
                                        <div class="template-icon me-3">
                                            <i class="fas fa-file-csv"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="fw-bold mb-1" style="color: var(--primary);">
                                                <i class="fas fa-download me-2"></i>Unduh Template
                                            </h6>
                                            <a href="{{ route('kelas.template') }}" class="btn btn-success btn-sm">
                                                <i class="fas fa-download me-1"></i>Download CSV Template
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex gap-3 mt-4">
                                    <a href="{{ route('kelas.index') }}" class="btn btn-back flex-fill">
                                        <i class="fas fa-arrow-left me-2"></i>Batal
                                    </a>
                                    <button type="submit" class="btn btn-primary flex-fill">
                                        <i class="fas fa-upload me-2"></i>Import
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
