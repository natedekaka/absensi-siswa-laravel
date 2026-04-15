<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Import Siswa - Absensi Siswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root { --primary: #4f46e5; --green: #25C185; }
        body { background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); min-height: 100vh; }
        .container-fluid { padding-top: 30px; }
        .card-custom { border: none; border-radius: 16px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); overflow: hidden; }
        .card-header-custom { background: linear-gradient(135deg, var(--primary) 0%, #6c5ce7 100%); padding: 1rem 1.5rem; display: flex; align-items: center; color: white; font-weight: 600; }
        .upload-zone { border: 2px dashed #ccc; border-radius: 16px; background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); transition: all 0.3s ease; cursor: pointer; }
        .upload-zone:hover { border-color: var(--green); background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%); }
        .upload-zone.dragover { border-color: var(--green); background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%); transform: scale(1.02); }
        .upload-icon { font-size: 3rem; color: var(--primary); }
        .template-info { background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%); border-radius: 12px; border-left: 4px solid var(--green); }
        .template-icon { width: 50px; height: 50px; background: var(--green); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: white; font-size: 1.5rem; }
        .format-info { background: #f8f9fa; border-radius: 12px; }
        .format-badge { display: inline-block; background: var(--primary); color: white; padding: 2px 8px; border-radius: 4px; font-size: 0.75rem; margin-right: 8px; }
        .kelas-info { background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%); border-radius: 12px; border-left: 4px solid #2196F3; }
        .kelas-table { background: white; border-radius: 8px; overflow: hidden; }
        .kelas-table thead { background: var(--primary); color: white; }
        .back-btn { position: fixed; top: 20px; left: 20px; width: 45px; height: 45px; border-radius: 14px; background: rgba(255,255,255,0.9); border: none; display: flex; align-items: center; justify-content: center; color: var(--primary); font-size: 18px; cursor: pointer; box-shadow: 0 4px 15px rgba(0,0,0,0.1); text-decoration: none; }
        .back-btn:hover { transform: scale(1.1); background: white; }
    </style>
</head>
<body>
    <a href="{{ route('siswa.index') }}" class="back-btn"><i class="fas fa-arrow-left"></i></a>

    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card-custom">
                    <div class="card-header-custom">
                        <i class="fas fa-upload me-2"></i>
                        <span>Unggah Data Siswa</span>
                    </div>
                    <div class="card-body p-4">
                        <div class="upload-zone p-5 text-center mb-4" id="dropZone">
                            <div class="upload-icon mb-3">
                                <i class="fas fa-cloud-upload-alt"></i>
                            </div>
                            <h5 class="fw-semibold" style="color: var(--primary);">Unggah File CSV</h5>
                            <p class="text-muted mb-3">Seret file ke sini atau klik untuk memilih</p>
                            <input type="file" name="csv_file" id="csv_file" class="form-control d-none" accept=".csv" required>
                            <button type="button" class="btn btn-primary" onclick="document.getElementById('csv_file').click()">
                                <i class="fas fa-folder-open me-2"></i>Pilih File
                            </button>
                            <p class="mt-3 mb-0 text-muted small" id="fileName">
                                <i class="fas fa-info-circle me-1"></i>
                                Format yang didukung: .CSV
                            </p>
                        </div>

                        <div class="template-info p-4 mb-4">
                            <div class="d-flex align-items-start">
                                <div class="template-icon me-3">
                                    <i class="fas fa-file-csv"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="fw-bold mb-2" style="color: var(--primary);">
                                        <i class="fas fa-download me-2"></i>Unduh Format Template
                                    </h6>
                                    <p class="text-muted small mb-3">
                                        Unduh file template di bawah untuk melihat format yang benar dalam menginput data siswa.
                                    </p>
                                    <a href="{{ route('siswa.template') }}" class="btn btn-success btn-sm">
                                        <i class="fas fa-file-download me-2"></i>Unduh Template CSV
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="format-info p-4">
                            <h6 class="fw-bold mb-3" style="color: var(--primary);">
                                <i class="fas fa-info-circle me-2"></i>Petunjuk Pengisian
                            </h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <span class="format-badge">Kolom 1</span>
                                        <span class="fw-semibold">NIS</span>
                                        <p class="text-muted small mb-0">Nomor Induk Siswa (wajib)</p>
                                    </div>
                                    <div class="mb-3">
                                        <span class="format-badge">Kolom 2</span>
                                        <span class="fw-semibold">NISN</span>
                                        <p class="text-muted small mb-0">Nomor Induk Siswa Nasional (wajib)</p>
                                    </div>
                                    <div class="mb-3">
                                        <span class="format-badge">Kolom 3</span>
                                        <span class="fw-semibold">Nama</span>
                                        <p class="text-muted small mb-0">Nama lengkap siswa (wajib)</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <span class="format-badge">Kolom 4</span>
                                        <span class="fw-semibold">Kelas ID</span>
                                        <p class="text-muted small mb-0">ID kelas dari tabel kelas (wajib)</p>
                                    </div>
                                    <div class="mb-3">
                                        <span class="format-badge">Kolom 5</span>
                                        <span class="fw-semibold">Jenis Kelamin</span>
                                        <p class="text-muted small mb-0">Laki-laki atau Perempuan</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if(count($kelasList) > 0)
                        <div class="kelas-info p-4 mt-4">
                            <h6 class="fw-bold mb-3" style="color: #2196F3;">
                                <i class="fas fa-door-open me-2"></i>Referensi Kelas ID
                            </h6>
                            <p class="text-muted small mb-3">Gunakan ID kelas berikut untuk mengisi Kolom 4 pada file CSV:</p>
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm kelas-table">
                                    <thead>
                                        <tr>
                                            <th class="text-center">ID Kelas</th>
                                            <th>Nama Kelas</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($kelasList as $kelas)
                                        <tr>
                                            <td class="text-center"><span class="badge bg-primary">{{ $kelas->id }}</span></td>
                                            <td>{{ $kelas->nama_kelas }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @endif

                        <form method="POST" enctype="multipart/form-data" id="importForm" action="{{ route('siswa.import.process') }}">
                            @csrf
                            <input type="file" name="file" id="csv_file_hidden" class="d-none" accept=".csv" required>
                            <div class="d-flex gap-2 mt-4">
                                <button type="submit" class="btn btn-primary" id="submitBtn" disabled>
                                    <i class="fas fa-upload me-2"></i>Import Data
                                </button>
                                <a href="{{ route('siswa.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>Kembali
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const dropZone = document.getElementById('dropZone');
        const fileInput = document.getElementById('csv_file');
        const fileInputHidden = document.getElementById('csv_file_hidden');
        const submitBtn = document.getElementById('submitBtn');
        const fileName = document.getElementById('fileName');

        dropZone.addEventListener('click', () => fileInput.click());

        dropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropZone.classList.add('dragover');
        });

        dropZone.addEventListener('dragleave', () => {
            dropZone.classList.remove('dragover');
        });

        dropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropZone.classList.remove('dragover');
            if (e.dataTransfer.files.length) {
                fileInput.files = e.dataTransfer.files;
                handleFileSelect(e.dataTransfer.files[0]);
            }
        });

        fileInput.addEventListener('change', (e) => {
            if (e.target.files.length) {
                handleFileSelect(e.target.files[0]);
            }
        });

        function handleFileSelect(file) {
            if (file.type === 'text/csv' || file.name.endsWith('.csv')) {
                fileInputHidden.files = fileInput.files;
                fileName.innerHTML = '<i class="fas fa-check-circle text-success me-1"></i> File dipilih: <strong>' + file.name + '</strong>';
                submitBtn.disabled = false;
            } else {
                fileName.innerHTML = '<i class="fas fa-exclamation-circle text-danger me-1"></i> Format file tidak valid. Silakan pilih file CSV.';
                submitBtn.disabled = true;
            }
        }
    </script>
</body>
</html>
