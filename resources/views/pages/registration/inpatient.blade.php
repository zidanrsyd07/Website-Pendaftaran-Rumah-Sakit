@extends('layouts.app')

@section('title', 'Pendaftaran Rawat Inap - RS Santa Anna')

@section('header')
    @include('components.page-header', ['title' => 'Rawat Inap', 'backUrl' => route('registration.select-type')])
@endsection

@section('content')
<div class="form-desktop-container">
    {{-- Progress Stepper --}}
    <div class="stepper" id="stepper">
        <div class="step-item active" data-step="1">
            <div class="step-circle">1</div>
            <div class="step-label">Data Pasien</div>
        </div>
        <div class="step-line"></div>
        <div class="step-item" data-step="2">
            <div class="step-circle">2</div>
            <div class="step-label">Perawatan</div>
        </div>
        <div class="step-line"></div>
        <div class="step-item" data-step="3">
            <div class="step-circle">3</div>
            <div class="step-label">Penjamin</div>
        </div>
        <div class="step-line"></div>
        <div class="step-item" data-step="4">
            <div class="step-circle">4</div>
            <div class="step-label">Konfirmasi</div>
        </div>
    </div>

    <form action="{{ route('registration.submit') }}" method="POST" id="inpatientForm">
        @csrf
        <input type="hidden" name="service_type" value="rawat_inap">

        {{-- Step 1: Data Pasien --}}
        <div class="form-step" id="step-1" style="padding: 16px;">
            <h2 style="font-size: 18px; font-weight: 700; margin-bottom: 6px; color: var(--color-gray-900);">Data Pasien</h2>
            <p style="font-size: 13px; color: var(--color-gray-500); margin-bottom: 20px;">Lengkapi identitas pasien yang akan dirawat.</p>

            {{-- Patient Status --}}
            <div class="form-group">
                <label class="option-card selected" id="inpStatusBaru" onclick="selectInpStatus('baru')">
                    <input type="radio" name="patient_status" value="baru" checked>
                    <div class="option-radio"></div>
                    <div class="option-content">
                        <h4>Pasien Baru</h4>
                        <p>Belum pernah terdaftar di RS Santa Anna</p>
                    </div>
                </label>
                <label class="option-card" id="inpStatusLama" onclick="selectInpStatus('lama')">
                    <input type="radio" name="patient_status" value="lama">
                    <div class="option-radio"></div>
                    <div class="option-content">
                        <h4>Pasien Lama</h4>
                        <p>Sudah memiliki nomor rekam medis</p>
                    </div>
                </label>
            </div>

            {{-- MR Lookup for pasien lama --}}
            <div id="inpLamaSection" style="display: none;">
                <div style="background: var(--color-primary-50); border: 1px solid var(--color-primary-200); border-radius: 14px; padding: 16px; margin-bottom: 20px;">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">Nomor Rekam Medis <span class="required">*</span></label>
                        <div style="display: flex; gap: 8px;">
                            <input type="text" name="medical_record_number" class="form-input" placeholder="Contoh: RM-001234" style="flex: 1;">
                            <button type="button" class="btn btn-primary btn-sm">Cari</button>
                        </div>
                        <div class="form-help">Masukkan nomor RM dari kartu pasien</div>
                    </div>
                </div>
            </div>

            {{-- Patient info form --}}
            <div id="inpBaruSection">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Nama Lengkap <span class="required">*</span></label>
                        <input type="text" name="full_name" class="form-input @error('full_name') error @enderror" value="{{ old('full_name') }}" required>
                        @error('full_name') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">NIK <span class="required">*</span></label>
                        <input type="text" name="nik" class="form-input @error('nik') error @enderror" value="{{ old('nik') }}" maxlength="16" pattern="[0-9]{16}" inputmode="numeric" required>
                        <div class="form-help">16 digit NIK KTP</div>
                        @error('nik') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Nomor HP <span class="required">*</span></label>
                        <input type="tel" name="phone" class="form-input @error('phone') error @enderror" value="{{ old('phone') }}" inputmode="tel" required>
                        @error('phone') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tanggal Lahir <span class="required">*</span></label>
                        <input type="date" name="birth_date" class="form-input @error('birth_date') error @enderror" value="{{ old('birth_date') }}" required>
                        @error('birth_date') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Jenis Kelamin <span class="required">*</span></label>
                    <select name="gender" class="form-select @error('gender') error @enderror" required>
                        <option value="">Pilih jenis kelamin</option>
                        <option value="L" {{ old('gender') === 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ old('gender') === 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    @error('gender') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Alamat <span class="required">*</span></label>
                    <textarea name="address" class="form-textarea @error('address') error @enderror" rows="3" required>{{ old('address') }}</textarea>
                    @error('address') <div class="form-error">{{ $message }}</div> @enderror
                </div>
            </div>

            <button type="button" class="btn btn-primary btn-block btn-lg" onclick="goToStep(2)">Lanjutkan</button>
        </div>

        {{-- Step 2: Perawatan --}}
        <div class="form-step" id="step-2" style="padding: 16px; display: none;">
            <h2 style="font-size: 18px; font-weight: 700; margin-bottom: 6px; color: var(--color-gray-900);">Informasi Perawatan</h2>
            <p style="font-size: 13px; color: var(--color-gray-500); margin-bottom: 20px;">Pilih kelas perawatan dan informasi rawat inap.</p>

            <div class="form-group">
                <label class="form-label">Kelas Perawatan <span class="required">*</span></label>
                <select name="room_class" class="form-select" required>
                    <option value="">Pilih kelas perawatan</option>
                    <option value="vip" {{ old('room_class') === 'vip' ? 'selected' : '' }}>VIP</option>
                    <option value="kelas_1" {{ old('room_class') === 'kelas_1' ? 'selected' : '' }}>Kelas 1</option>
                    <option value="kelas_2" {{ old('room_class') === 'kelas_2' ? 'selected' : '' }}>Kelas 2</option>
                    <option value="kelas_3" {{ old('room_class') === 'kelas_3' ? 'selected' : '' }}>Kelas 3</option>
                </select>
                @error('room_class') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Dokter yang Merujuk</label>
                <input type="text" name="referring_doctor" class="form-input" value="{{ old('referring_doctor') }}" placeholder="Nama dokter yang memberikan rujukan">
            </div>

            <div class="form-group">
                <label class="form-label">Diagnosa Awal / Keperluan</label>
                <textarea name="notes" class="form-textarea" rows="3" placeholder="Jelaskan alasan atau keperluan rawat inap">{{ old('notes') }}</textarea>
            </div>

            <div class="form-group">
                <label class="form-label">Kontak Penanggung Jawab</label>
                <input type="text" name="guardian_contact" class="form-input" value="{{ old('guardian_contact') }}" placeholder="Nama & nomor HP penanggung jawab pasien">
            </div>

            <div style="display: flex; gap: 10px; margin-top: 20px;">
                <button type="button" class="btn btn-outline btn-block" onclick="goToStep(1)">Kembali</button>
                <button type="button" class="btn btn-primary btn-block" onclick="goToStep(3)">Lanjutkan</button>
            </div>
        </div>

        {{-- Step 3: Penjamin --}}
        <div class="form-step" id="step-3" style="padding: 16px; display: none;">
            <h2 style="font-size: 18px; font-weight: 700; margin-bottom: 6px; color: var(--color-gray-900);">Asuransi &amp; Penjamin</h2>
            <p style="font-size: 13px; color: var(--color-gray-500); margin-bottom: 20px;">Pilih jenis penjamin biaya rawat inap.</p>

            <div class="form-group">
                <label class="option-card" id="payUmum" onclick="selectInpPayment('umum')">
                    <input type="radio" name="patient_type" value="umum" {{ old('patient_type', 'umum') === 'umum' ? 'checked' : '' }} required>
                    <div class="option-radio"></div>
                    <div class="option-content">
                        <h4>Umum / Mandiri</h4>
                        <p>Membayar biaya secara mandiri</p>
                    </div>
                </label>

                <label class="option-card" id="payAsuransi" onclick="selectInpPayment('asuransi')">
                    <input type="radio" name="patient_type" value="asuransi" {{ old('patient_type') === 'asuransi' ? 'checked' : '' }}>
                    <div class="option-radio"></div>
                    <div class="option-content">
                        <h4>Asuransi Swasta</h4>
                        <p>Menggunakan asuransi kesehatan swasta</p>
                    </div>
                </label>
            </div>

            {{-- Insurance fields --}}
            <div id="inpInsuranceFields" style="display: none;">
                <div class="form-group">
                    <label class="form-label">Nama Asuransi / Penjamin <span class="required">*</span></label>
                    <input type="text" name="insurance_name" class="form-input" value="{{ old('insurance_name') }}" id="inpInsuranceName">
                    @error('insurance_name') <div class="form-error">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Nomor Peserta / Polis <span class="required">*</span></label>
                    <input type="text" name="insurance_number" class="form-input" value="{{ old('insurance_number') }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Nomor Surat Jaminan</label>
                    <input type="text" name="insurance_referral" class="form-input" value="{{ old('insurance_referral') }}" placeholder="Opsional">
                </div>
            </div>

            <div style="display: flex; gap: 10px; margin-top: 20px;">
                <button type="button" class="btn btn-outline btn-block" onclick="goToStep(2)">Kembali</button>
                <button type="button" class="btn btn-primary btn-block" onclick="goToStep(4)">Lanjutkan</button>
            </div>
        </div>

        {{-- Step 4: Konfirmasi --}}
        <div class="form-step" id="step-4" style="padding: 16px; display: none;">
            <h2 style="font-size: 18px; font-weight: 700; margin-bottom: 6px; color: var(--color-gray-900);">Konfirmasi Data</h2>
            <p style="font-size: 13px; color: var(--color-gray-500); margin-bottom: 20px;">Periksa kembali data sebelum mengirim.</p>

            <div style="background: var(--color-gray-50); border: 1px solid var(--color-gray-200); border-radius: 14px; padding: 16px; margin-bottom: 20px;">
                <div id="confirmationSummary"></div>
            </div>

            <div class="form-group">
                <label class="checkbox-label">
                    <input type="checkbox" name="consent" id="inpConsentCheckbox" required>
                    <span>Saya menyatakan bahwa data yang saya isi adalah benar dan saya menyetujui penggunaan data ini untuk proses pendaftaran rawat inap di RS Santa Anna.</span>
                </label>
            </div>

            <div class="notice-banner" style="margin: 0 0 16px;">
                Data pendaftaran ini merupakan data sementara dan belum terhubung ke sistem SIMRS RS Santa Anna.
            </div>

            <div style="display: flex; gap: 10px;">
                <button type="button" class="btn btn-outline btn-block" onclick="goToStep(3)">Kembali</button>
                <button type="submit" class="btn btn-primary btn-block" id="inpSubmitBtn" disabled>Kirim Pendaftaran</button>
            </div>
        </div>
    </form>

    @push('scripts')
    <script>
        let currentStep = 1;

        function goToStep(step) {
            if (step > currentStep) {
                if (!validateStep(currentStep)) return;
            }

            document.querySelectorAll('.form-step').forEach(s => s.style.display = 'none');
            document.getElementById('step-' + step).style.display = 'block';

            document.querySelectorAll('.step-item').forEach(item => {
                const itemStep = parseInt(item.dataset.step);
                item.classList.remove('active', 'completed');
                if (itemStep < step) {
                    item.classList.add('completed');
                } else if (itemStep === step) {
                    item.classList.add('active');
                }
            });

            currentStep = step;
            if (step === 4) buildConfirmationSummary();
            window.scrollTo(0, 0);
        }

        function validateStep(step) {
            const stepEl = document.getElementById('step-' + step);
            const requiredFields = stepEl.querySelectorAll('[required]:not([disabled])');
            let valid = true;

            requiredFields.forEach(field => {
                if (field.type === 'checkbox') { if (!field.checked) valid = false; return; }
                if (field.type === 'radio') { return; }
                if (!field.value || field.value.trim() === '') {
                    field.classList.add('error');
                    valid = false;
                } else {
                    field.classList.remove('error');
                }
            });

            if (!valid) {
                const firstError = stepEl.querySelector('.error');
                if (firstError) firstError.focus();
            }

            return valid;
        }

        function selectInpStatus(status) {
            const lama = document.getElementById('inpLamaSection');
            const baru = document.getElementById('inpBaruSection');
            const lamaCard = document.getElementById('inpStatusLama');
            const baruCard = document.getElementById('inpStatusBaru');

            if (status === 'lama') {
                lama.style.display = 'block';
                baru.style.display = 'none';
                lamaCard.classList.add('selected');
                baruCard.classList.remove('selected');
            } else {
                lama.style.display = 'none';
                baru.style.display = 'block';
                baruCard.classList.add('selected');
                lamaCard.classList.remove('selected');
            }
        }

        function selectInpPayment(type) {
            const insuranceFields = document.getElementById('inpInsuranceFields');
            document.querySelectorAll('#step-3 .option-card').forEach(card => {
                const radio = card.querySelector('input[type="radio"]');
                card.classList.toggle('selected', radio && radio.checked);
            });
            setTimeout(() => {
                document.querySelectorAll('#step-3 .option-card').forEach(card => {
                    const radio = card.querySelector('input[type="radio"]');
                    card.classList.toggle('selected', radio && radio.checked);
                });
            }, 10);

            if (type === 'umum') {
                insuranceFields.style.display = 'none';
                insuranceFields.querySelectorAll('input').forEach(f => f.removeAttribute('required'));
            } else {
                insuranceFields.style.display = 'block';
                document.getElementById('inpInsuranceName').setAttribute('required', 'required');
            }
        }

        function buildConfirmationSummary() {
            const form = document.getElementById('inpatientForm');
            const data = new FormData(form);

            const roomClassMap = { 'vip': 'VIP', 'kelas_1': 'Kelas 1', 'kelas_2': 'Kelas 2', 'kelas_3': 'Kelas 3' };
            const paymentMap = { 'umum': 'Umum / Mandiri', 'asuransi': 'Asuransi Swasta' };

            const rows = [
                ['Nama Lengkap', data.get('full_name')],
                ['NIK', data.get('nik')],
                ['No. HP', data.get('phone')],
                ['Jenis Kelamin', data.get('gender') === 'L' ? 'Laki-laki' : 'Perempuan'],
                ['Jenis Layanan', 'Rawat Inap'],
                ['Kelas Perawatan', roomClassMap[data.get('room_class')] || '-'],
                ['Jenis Penjamin', paymentMap[data.get('patient_type')] || '-'],
            ];

            if (data.get('patient_type') !== 'umum') {
                rows.push(['Nama Asuransi', data.get('insurance_name') || '-']);
                rows.push(['No. Peserta/Polis', data.get('insurance_number') || '-']);
            }

            if (data.get('referring_doctor')) rows.push(['Dokter Perujuk', data.get('referring_doctor')]);
            if (data.get('notes')) rows.push(['Keperluan', data.get('notes')]);

            let html = '';
            rows.forEach(([label, value]) => {
                html += `<div class="confirmation-row"><span class="label">${label}</span><span class="value">${value || '-'}</span></div>`;
            });

            document.getElementById('confirmationSummary').innerHTML = html;
        }

        // Init
        document.addEventListener('DOMContentLoaded', function() {
            selectInpStatus('baru');
            const checkedType = document.querySelector('input[name="patient_type"]:checked');
            if (checkedType) selectInpPayment(checkedType.value);
            else selectInpPayment('umum');

            document.getElementById('inpConsentCheckbox').addEventListener('change', function() {
                document.getElementById('inpSubmitBtn').disabled = !this.checked;
            });
        });
    </script>
    @endpush
</div>
@endsection
