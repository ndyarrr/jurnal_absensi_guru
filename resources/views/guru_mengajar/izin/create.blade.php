@extends('layouts.guru_mengajar')

@section('title', 'Buat Pengajuan Izin')
@section('page-title', 'Form Pengajuan Izin Guru')
@section('page-subtitle', 'Isi data ketidakhadiran dan unggah dokumen/foto bukti izin')

@section('content')

    <div class="gm-card" style="max-width: 800px; margin: 0 auto;">
        <div class="gm-card-header" style="display: flex; align-items: center; justify-content: space-between;">
            <h3 class="gm-card-title">
                <i class="fa-solid fa-pen-to-square" style="color: var(--dash-navy);"></i> 
                Formulir Surat Pengajuan Izin
            </h3>
            <a href="{{ route('guru-mengajar.izin') }}" class="gm-btn gm-btn-outline">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Riwayat
            </a>
        </div>

        <div class="gm-card-body">
            @if($errors->any())
                <div class="gm-alert error" style="margin-bottom: 20px;">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <div>
                        <strong>Terdapat kesalahan pada inputan Anda:</strong>
                        <ul style="margin-top: 6px; padding-left: 20px; font-size: 0.85rem;">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <form action="{{ route('guru-mengajar.izin.store') }}" method="POST" enctype="multipart/form-data" id="formPengajuanIzin">
                @csrf

                <!-- Nama Guru (Read-only Info) -->
                <div style="margin-bottom: 20px; background: #f8fafc; padding: 12px 16px; border-radius: 10px; border: 1px solid #e2e8f0;">
                    <span style="font-size: 0.75rem; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Pemohon Izin:</span>
                    <div style="font-size: 1rem; font-weight: 800; color: #1e293b; margin-top: 2px;">
                        {{ $guru->nama_guru ?? auth()->user()->name }}
                        @if($guru && $guru->nip)
                            <span style="font-size: 0.8rem; font-weight: 600; color: #64748b; margin-left: 6px;">(NIP: {{ $guru->nip }})</span>
                        @endif
                    </div>
                </div>

                <!-- 1. Alasan / Kategori Izin -->
                <div style="margin-bottom: 20px;">
                    <label for="kategori_izin" style="display: block; font-weight: 800; font-size: 0.875rem; color: #334155; margin-bottom: 8px;">
                        Alasan / Jenis Izin <span style="color: #dc2626;">*</span>
                    </label>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 10px;">
                        <label class="kategori-radio-card" style="position: relative; cursor: pointer;">
                            <input type="radio" name="kategori_izin" value="sakit" {{ old('kategori_izin', 'sakit') == 'sakit' ? 'checked' : '' }} required style="position: absolute; opacity: 0;">
                            <div class="radio-card-content" style="padding: 14px; border: 2px solid #cbd5e1; border-radius: 12px; text-align: center; transition: all 0.2s ease;">
                                <div style="font-size: 1.5rem; color: #dc2626; margin-bottom: 4px;"><i class="fa-solid fa-hospital-user"></i></div>
                                <div style="font-weight: 800; font-size: 0.9rem; color: #1e293b;">Sakit</div>
                                <div style="font-size: 0.72rem; color: #64748b; margin-top: 2px;">Surat Dokter / Sakit</div>
                            </div>
                        </label>

                        <label class="kategori-radio-card" style="position: relative; cursor: pointer;">
                            <input type="radio" name="kategori_izin" value="dinas" {{ old('kategori_izin') == 'dinas' ? 'checked' : '' }} style="position: absolute; opacity: 0;">
                            <div class="radio-card-content" style="padding: 14px; border: 2px solid #cbd5e1; border-radius: 12px; text-align: center; transition: all 0.2s ease;">
                                <div style="font-size: 1.5rem; color: #2563eb; margin-bottom: 4px;"><i class="fa-solid fa-briefcase"></i></div>
                                <div style="font-weight: 800; font-size: 0.9rem; color: #1e293b;">Dinas</div>
                                <div style="font-size: 0.72rem; color: #64748b; margin-top: 2px;">Tugas Luar / Sekolah</div>
                            </div>
                        </label>

                        <label class="kategori-radio-card" style="position: relative; cursor: pointer;">
                            <input type="radio" name="kategori_izin" value="cuti" {{ old('kategori_izin') == 'cuti' ? 'checked' : '' }} style="position: absolute; opacity: 0;">
                            <div class="radio-card-content" style="padding: 14px; border: 2px solid #cbd5e1; border-radius: 12px; text-align: center; transition: all 0.2s ease;">
                                <div style="font-size: 1.5rem; color: #16a34a; margin-bottom: 4px;"><i class="fa-solid fa-mug-hot"></i></div>
                                <div style="font-weight: 800; font-size: 0.9rem; color: #1e293b;">Cuti</div>
                                <div style="font-size: 0.72rem; color: #64748b; margin-top: 2px;">Permohonan Cuti</div>
                            </div>
                        </label>

                        <label class="kategori-radio-card" style="position: relative; cursor: pointer;">
                            <input type="radio" name="kategori_izin" value="acara_keluarga" {{ old('kategori_izin') == 'acara_keluarga' ? 'checked' : '' }} style="position: absolute; opacity: 0;">
                            <div class="radio-card-content" style="padding: 14px; border: 2px solid #cbd5e1; border-radius: 12px; text-align: center; transition: all 0.2s ease;">
                                <div style="font-size: 1.5rem; color: #9333ea; margin-bottom: 4px;"><i class="fa-solid fa-users"></i></div>
                                <div style="font-weight: 800; font-size: 0.9rem; color: #1e293b;">Acara Keluarga</div>
                                <div style="font-size: 0.72rem; color: #64748b; margin-top: 2px;">Urusan / Haatan</div>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- 2. Rentang Tanggal Izin -->
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 20px;">
                    <div>
                        <label for="tanggal_mulai" style="display: block; font-weight: 800; font-size: 0.875rem; color: #334155; margin-bottom: 6px;">
                            Tanggal Mulai <span style="color: #dc2626;">*</span>
                        </label>
                        <input type="date" name="tanggal_mulai" id="tanggal_mulai" class="gm-input" value="{{ old('tanggal_mulai', date('Y-m-d')) }}" required style="width: 100%;">
                    </div>

                    <div>
                        <label for="tanggal_selesai" style="display: block; font-weight: 800; font-size: 0.875rem; color: #334155; margin-bottom: 6px;">
                            Tanggal Selesai <span style="color: #dc2626;">*</span>
                        </label>
                        <input type="date" name="tanggal_selesai" id="tanggal_selesai" class="gm-input" value="{{ old('tanggal_selesai', date('Y-m-d')) }}" required style="width: 100%;">
                    </div>
                </div>

                <!-- 3. Alasan / Rincian Keterangan -->
                <div style="margin-bottom: 20px;">
                    <label for="alasan_izin" style="display: block; font-weight: 800; font-size: 0.875rem; color: #334155; margin-bottom: 6px;">
                        Keterangan Rincian Alasan <span style="color: #dc2626;">*</span>
                    </label>
                    <textarea name="alasan_izin" id="alasan_izin" class="gm-input" rows="4" required placeholder="Tuliskan penjelasan rincian permohonan izin Anda di sini..." style="width: 100%; line-height: 1.5; padding: 12px;">{{ old('alasan_izin') }}</textarea>
                </div>

                <!-- 4. Upload Bukti Foto / Dokumen (Dengan Live Preview) -->
                <div style="margin-bottom: 24px;">
                    <label style="display: block; font-weight: 800; font-size: 0.875rem; color: #334155; margin-bottom: 6px;">
                        Unggah Bukti Foto / Dokumen <span style="color: #64748b; font-weight: 600;">(Opsional)</span>
                    </label>
                    <p style="font-size: 0.8rem; color: #64748b; margin-bottom: 10px;">Format: Surat Dokter, Surat Tugas, atau foto dokumen (.jpg, .png, .webp, .pdf). Maksimal 5 MB.</p>

                    <!-- Dropzone Container -->
                    <div id="dropzoneArea" style="border: 2px dashed #cbd5e1; border-radius: 14px; padding: 24px; text-align: center; background: #f8fafc; cursor: pointer; transition: all 0.2s ease; position: relative;">
                        <input type="file" name="bukti_surat" id="buktiSuratInput" accept="image/jpeg,image/png,image/webp,application/pdf" style="position: absolute; top:0; left:0; width:100%; height:100%; opacity:0; cursor:pointer;">
                        
                        <div id="uploadPrompt">
                            <div style="font-size: 2.2rem; color: #3b82f6; margin-bottom: 8px;">
                                <i class="fa-solid fa-cloud-arrow-up"></i>
                            </div>
                            <span style="font-weight: 800; color: #1e293b; font-size: 0.95rem; display: block;">Klik atau tarik file bukti di sini</span>
                            <span style="font-size: 0.8rem; color: #64748b; display: block; margin-top: 4px;">Pilih foto bukti sakit / dinas / dokumen</span>
                        </div>

                        <!-- Live Preview Box -->
                        <div id="previewContainer" style="display: none; flex-direction: column; align-items: center; justify-content: center; position: relative; z-index: 10;">
                            <div style="position: relative; display: inline-block;">
                                <img id="imagePreview" src="" alt="Pratinjau Foto Bukti" style="max-width: 100%; max-height: 280px; border-radius: 12px; object-fit: contain; box-shadow: 0 4px 12px rgba(0,0,0,0.1); border: 2px solid #ffffff; display: none;">
                                <div id="pdfPreviewBadge" style="display: none; background: #eff6ff; color: #1d4ed8; padding: 16px 24px; border-radius: 12px; border: 1px solid #bfdbfe; font-weight: 800;">
                                    <i class="fa-solid fa-file-pdf" style="font-size: 2rem; color: #dc2626; display: block; margin-bottom: 6px;"></i>
                                    <span id="pdfFileName">dokumen.pdf</span>
                                </div>
                            </div>
                            <div style="margin-top: 10px; display: flex; gap: 10px;">
                                <button type="button" id="btnRemovePreview" class="gm-btn" style="background: #fee2e2; color: #dc2626; border: 1px solid #fca5a5; font-size: 0.8rem; padding: 6px 14px; position: relative; z-index: 20;">
                                    <i class="fa-solid fa-trash"></i> Hapus Foto
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div style="display: flex; justify-content: flex-end; gap: 12px; border-top: 1px solid #e2e8f0; padding-top: 20px;">
                    <a href="{{ route('guru-mengajar.izin') }}" class="gm-btn gm-btn-outline">
                        Batal
                    </a>
                    <button type="submit" class="gm-btn gm-btn-navy" style="padding: 10px 24px;">
                        <i class="fa-solid fa-paper-plane"></i> Kirim Pengajuan Izin
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Live Preview Script & Radio Card Styling -->
    <style>
        .kategori-radio-card input:checked + .radio-card-content {
            border-color: #2563eb !important;
            background-color: #eff6ff !important;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.15) !important;
        }
        #dropzoneArea:hover {
            border-color: #2563eb !important;
            background-color: #f1f5f9 !important;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const fileInput = document.getElementById('buktiSuratInput');
            const uploadPrompt = document.getElementById('uploadPrompt');
            const previewContainer = document.getElementById('previewContainer');
            const imagePreview = document.getElementById('imagePreview');
            const pdfPreviewBadge = document.getElementById('pdfPreviewBadge');
            const pdfFileName = document.getElementById('pdfFileName');
            const btnRemove = document.getElementById('btnRemovePreview');

            if (fileInput) {
                fileInput.addEventListener('change', function (e) {
                    const file = e.target.files[0];
                    if (!file) {
                        resetPreview();
                        return;
                    }

                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function (evt) {
                            imagePreview.src = evt.target.result;
                            imagePreview.style.display = 'block';
                            pdfPreviewBadge.style.display = 'none';
                            uploadPrompt.style.display = 'none';
                            previewContainer.style.display = 'flex';
                        };
                        reader.readAsDataURL(file);
                    } else if (file.type === 'application/pdf') {
                        imagePreview.style.display = 'none';
                        pdfFileName.textContent = file.name;
                        pdfPreviewBadge.style.display = 'block';
                        uploadPrompt.style.display = 'none';
                        previewContainer.style.display = 'flex';
                    }
                });
            }

            if (btnRemove) {
                btnRemove.addEventListener('click', function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    resetPreview();
                });
            }

            function resetPreview() {
                if (fileInput) fileInput.value = '';
                if (imagePreview) imagePreview.src = '';
                if (imagePreview) imagePreview.style.display = 'none';
                if (pdfPreviewBadge) pdfPreviewBadge.style.display = 'none';
                if (uploadPrompt) uploadPrompt.style.display = 'block';
                if (previewContainer) previewContainer.style.display = 'none';
            }
        });
    </script>

@endsection
