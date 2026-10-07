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
            <div class="step-label">Konfirmasi</div>
        </div>
    </div>

    <form action="{{ route('registration.submit') }}" method="POST" id="inpatientForm">
        @csrf
        <input type="hidden" name="service_type" value="rawat_inap">

        {{-- Step 1: Data Pasien --}}
        <div class="form-step" id="step-1" style="padding: 16px;">
            <h2 style="font-size: 17px; font-weight: 700; margin-bottom: 16px; color: var(--color-gray-800);">Data Pasien</h2>

            <div class="form-group">
                <label class="form-label">Nama Lengkap <span class="required">*</span></label>
                <input type="text" name="full_name" class="form-input @error('full_name') error @enderror" value="{{ old('full_name') }}" required>
                @error('full_name') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">NIK <span class="required">*</span></label>
                <input type="text" name="nik" class="form-input @error('nik') error @enderror" value="{{ old('nik') }}" maxlength="16" pattern="[0-9]{16}" inputmode="numeric" required>
                <div class="form-help">Masukkan 16 digit Nomor Induk Kependudukan</div>
                @error('nik') <div class="form-error">{{ $message }}</div> @enderror
            </div>

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

            <button type="button" class="btn btn-primary btn-block btn-lg" onclick="goToStep(2)">Lanjutkan</button>
        </div>

        {{-- Step 2: Perawatan --}}
        <div class="form-step" id="step-2" style="padding: 16px; display: none;">
            <h2 style="font-size: 17px; font-weight: 700; margin-bottom: 16px; color: var(--color-gray-800);">Informasi Perawatan</h2>

            <div class="form-group">
                <label class="form-label">Jenis Pasien <span class="required">*</span></label>
                <label class="option-card" id="patientTypeUmum" onclick="selectPatientType('umum')">
                    <input type="radio" name="patient_type" value="umum" {{ old('patient_type', 'umum') === 'umum' ? 'checked' : '' }} required>
                    <div class="option-radio"></div>
                    <div class="option-content">
                        <h4>Pasien Umum</h4>
                        <p>Tanpa menggunakan asuransi</p>
                    </div>
                </label>
                <label class="option-card" id="patientTypeAsuransi" onclick="selectPatientType('asuransi')">
                    <input type="radio" name="patient_type" value="asuransi" {{ old('patient_type') === 'asuransi' ? 'checked' : '' }}>
                    <div class="option-radio"></div>
                    <div class="option-content">
                        <h4>Asuransi</h4>
                        <p>Menggunakan asuransi kesehatan</p>
                    </div>
                </label>
            </div>

            {{-- Dynamic insurance field --}}
            <div class="form-group" id="insuranceField" style="display: none;">
                <label class="form-label">Nama Asuransi <span class="required">*</span></label>
                <input type="text" name="insurance_name" class="form-input" value="{{ old('insurance_name') }}">
                @error('insurance_name') <div class="form-error">{{ $message }}</div> @enderror
            </div>

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
                <label class="form-label">Keperluan / Keterangan</label>
                <textarea name="notes" class="form-textarea" rows="3" placeholder="Jelaskan keperluan rawat inap Anda">{{ old('notes') }}</textarea>
            </div>

            <div style="display: flex; gap: 10px; margin-top: 20px;">
                <button type="button" class="btn btn-outline btn-block" onclick="goToStep(1)">Kembali</button>
                <button type="button" class="btn btn-primary btn-block" onclick="goToStep(3)">Lanjutkan</button>
            </div>
        </div>

        {{-- Step 3: Konfirmasi --}}
        <div class="form-step" id="step-3" style="padding: 16px; display: none;">
            <h2 style="font-size: 17px; font-weight: 700; margin-bottom: 16px; color: var(--color-gray-800);">Konfirmasi Data</h2>

            <div style="background: var(--color-gray-50); border-radius: 10px; padding: 14px; margin-bottom: 16px;">
                <div id="confirmationSummary"></div>
            </div>

            <div class="notice-banner" style="margin: 0 0 16px;">
                Data pendaftaran ini merupakan data sementara dan belum terhubung ke sistem SIMRS RS Santa Anna.
            </div>

            <div style="display: flex; gap: 10px;">
                <button type="button" class="btn btn-outline btn-block" onclick="goToStep(2)">Kembali</button>
                <button type="submit" class="btn btn-primary btn-block">Kirim Pendaftaran</button>
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
            if (step === 3) buildConfirmationSummary();
            window.scrollTo(0, 0);
        }

        function validateStep(step) {
            const stepEl = document.getElementById('step-' + step);
            const requiredFields = stepEl.querySelectorAll('[required]');
            let valid = true;

            requiredFields.forEach(field => {
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

        function selectPatientType(type) {
            const insuranceField = document.getElementById('insuranceField');
            const umumCard = document.getElementById('patientTypeUmum');
            const asuransiCard = document.getElementById('patientTypeAsuransi');

            if (type === 'asuransi') {
                insuranceField.style.display = 'block';
                insuranceField.querySelector('input').setAttribute('required', 'required');
                asuransiCard.classList.add('selected');
                umumCard.classList.remove('selected');
            } else {
                insuranceField.style.display = 'none';
                insuranceField.querySelector('input').removeAttribute('required');
                umumCard.classList.add('selected');
                asuransiCard.classList.remove('selected');
            }
        }

        function buildConfirmationSummary() {
            const form = document.getElementById('inpatientForm');
            const data = new FormData(form);

            const roomClassMap = {
                'vip': 'VIP',
                'kelas_1': 'Kelas 1',
                'kelas_2': 'Kelas 2',
                'kelas_3': 'Kelas 3',
            };

            const patientType = data.get('patient_type') === 'asuransi' ? 'Asuransi' : 'Pasien Umum';

            const rows = [
                ['Nama Lengkap', data.get('full_name')],
                ['NIK', data.get('nik')],
                ['No. HP', data.get('phone')],
                ['Jenis Kelamin', data.get('gender') === 'L' ? 'Laki-laki' : 'Perempuan'],
                ['Jenis Layanan', 'Rawat Inap'],
                ['Jenis Pasien', patientType],
            ];

            if (data.get('patient_type') === 'asuransi') {
                rows.push(['Nama Asuransi', data.get('insurance_name') || '-']);
            }

            rows.push(['Kelas Perawatan', roomClassMap[data.get('room_class')] || '-']);

            if (data.get('notes')) {
                rows.push(['Keterangan', data.get('notes')]);
            }

            let html = '';
            rows.forEach(([label, value]) => {
                html += `
                    <div class="confirmation-row">
                        <span class="label">${label}</span>
                        <span class="value">${value || '-'}</span>
                    </div>
                `;
            });

            document.getElementById('confirmationSummary').innerHTML = html;
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            const checkedType = document.querySelector('input[name="patient_type"]:checked');
            if (checkedType) {
                selectPatientType(checkedType.value);
            } else {
                selectPatientType('umum');
            }
        });
    </script>
    @endpush
</div>
@endsection
