@extends('layouts.app')

@section('title', 'Pendaftaran Rawat Jalan - RS Santa Anna')

@section('header')
    @include('components.page-header', ['title' => 'Rawat Jalan', 'backUrl' => route('registration.select-type')])
@endsection

@section('content')
<div class="form-desktop-container">
    {{-- Progress Stepper --}}
    <div class="stepper" id="stepper">
        <div class="step-item active" data-step="1">
            <div class="step-circle">1</div>
            <div class="step-label">Identitas</div>
        </div>
        <div class="step-line"></div>
        <div class="step-item" data-step="2">
            <div class="step-circle">2</div>
            <div class="step-label">Kunjungan</div>
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

    <form action="{{ route('registration.submit') }}" method="POST" id="registrationForm">
        @csrf
        <input type="hidden" name="service_type" value="rawat_jalan">

        {{-- ══════════════════════════════════════════
           STEP 1: Status & Identitas Pasien
           ══════════════════════════════════════════ --}}
        <div class="form-step" id="step-1" style="padding: 16px;">
            <h2 style="font-size: 18px; font-weight: 700; margin-bottom: 6px; color: var(--color-gray-900);">Identitas Pasien</h2>
            <p style="font-size: 13px; color: var(--color-gray-500); margin-bottom: 20px;">Apakah Anda sudah pernah terdaftar sebagai pasien di RS Santa Anna?</p>

            {{-- Patient Status Selection --}}
            <div class="form-group">
                <label class="option-card" id="patientStatusLama" onclick="selectPatientStatus('lama')">
                    <input type="radio" name="patient_status" value="lama">
                    <div class="option-radio"></div>
                    <div class="option-content">
                        <h4>Pasien Lama</h4>
                        <p>Sudah pernah berobat dan memiliki nomor rekam medis</p>
                    </div>
                </label>
                <label class="option-card" id="patientStatusBaru" onclick="selectPatientStatus('baru')">
                    <input type="radio" name="patient_status" value="baru" checked>
                    <div class="option-radio"></div>
                    <div class="option-content">
                        <h4>Pasien Baru</h4>
                        <p>Belum pernah terdaftar di RS Santa Anna</p>
                    </div>
                </label>
            </div>

            {{-- Pasien Lama: MR Number Lookup --}}
            <div id="patientLamaSection" style="display: none;">
                <div style="background: var(--color-primary-50); border: 1px solid var(--color-primary-200); border-radius: 14px; padding: 16px; margin-bottom: 20px;">
                    <div class="form-group" style="margin-bottom: 12px;">
                        <label class="form-label">Nomor Rekam Medis <span class="required">*</span></label>
                        <div style="display: flex; gap: 8px;">
                            <input type="text" name="medical_record_number" id="mrInput" class="form-input" placeholder="Contoh: RM-001234" style="flex: 1;">
                            <button type="button" class="btn btn-primary btn-sm" onclick="searchPatient()" id="searchMrBtn">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 16px; height: 16px;"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                                Cari
                            </button>
                        </div>
                        <div class="form-help">Masukkan nomor rekam medis yang tertera pada kartu pasien Anda</div>
                    </div>

                    {{-- Patient data display (shown after search) --}}
                    <div id="patientDataResult" style="display: none;">
                        <div style="background: #fff; border-radius: 10px; padding: 14px;">
                            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
                                <div style="width: 8px; height: 8px; border-radius: 50%; background: var(--color-success);"></div>
                                <span style="font-size: 12px; font-weight: 600; color: var(--color-success);">Data Pasien Ditemukan</span>
                            </div>
                            <div id="patientDataContent"></div>
                        </div>
                    </div>
                    <div id="patientNotFound" style="display: none;">
                        <div style="background: var(--color-danger-light); border-radius: 10px; padding: 12px; font-size: 13px; color: var(--color-danger);">
                            Data pasien tidak ditemukan. Periksa kembali nomor rekam medis Anda atau daftar sebagai pasien baru.
                        </div>
                    </div>
                </div>
            </div>

            {{-- Pasien Baru: Full Form --}}
            <div id="patientBaruSection">
                <div class="form-row">
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
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Tempat Lahir <span class="required">*</span></label>
                        <input type="text" name="birth_place" class="form-input" value="{{ old('birth_place') }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tanggal Lahir <span class="required">*</span></label>
                        <input type="date" name="birth_date" class="form-input @error('birth_date') error @enderror" value="{{ old('birth_date') }}" required>
                        @error('birth_date') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="form-row">
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
                        <label class="form-label">Nomor HP <span class="required">*</span></label>
                        <input type="tel" name="phone" class="form-input @error('phone') error @enderror" value="{{ old('phone') }}" inputmode="tel" placeholder="08xxxxxxxxxx" required>
                        @error('phone') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Alamat Lengkap <span class="required">*</span></label>
                    <textarea name="address" class="form-textarea @error('address') error @enderror" rows="3" required>{{ old('address') }}</textarea>
                    @error('address') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-input" value="{{ old('email') }}" placeholder="opsional">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Kontak Darurat</label>
                        <input type="text" name="emergency_contact" class="form-input" value="{{ old('emergency_contact') }}" placeholder="Nama & nomor HP">
                    </div>
                </div>
            </div>

            <button type="button" class="btn btn-primary btn-block btn-lg" onclick="goToStep(2)">Lanjutkan</button>
        </div>

        {{-- ══════════════════════════════════════════
           STEP 2: Data Kunjungan
           ══════════════════════════════════════════ --}}
        <div class="form-step" id="step-2" style="padding: 16px; display: none;">
            <h2 style="font-size: 18px; font-weight: 700; margin-bottom: 6px; color: var(--color-gray-900);">Data Kunjungan</h2>
            <p style="font-size: 13px; color: var(--color-gray-500); margin-bottom: 20px;">Pilih poliklinik, dokter, dan jadwal kunjungan Anda.</p>

            <div class="form-group">
                <label class="form-label">Poliklinik <span class="required">*</span></label>
                <select name="polyclinic_id" class="form-select" id="polyclinicSelect" required onchange="loadDoctors(this.value)">
                    <option value="">Pilih poliklinik</option>
                    @foreach($polyclinics as $poli)
                        <option value="{{ $poli['id'] }}" {{ (old('polyclinic_id', $prefill['polyclinic_id'] ?? '') == $poli['id']) ? 'selected' : '' }}>{{ $poli['name'] }}</option>
                    @endforeach
                </select>
                @error('polyclinic_id') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Dokter <span class="required">*</span></label>
                <select name="doctor_id" class="form-select" id="doctorSelect" required>
                    <option value="">Pilih dokter</option>
                    @foreach($doctors as $doc)
                        <option value="{{ $doc['id'] }}" data-polyclinic="{{ $doc['polyclinic_id'] }}" data-schedules="{{ json_encode($doc['schedules']) }}" {{ (old('doctor_id', $prefill['doctor_id'] ?? '') == $doc['id']) ? 'selected' : '' }}>
                            {{ $doc['name'] }} - {{ $doc['specialization'] }}
                        </option>
                    @endforeach
                </select>
                @error('doctor_id') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Tanggal Kunjungan <span class="required">*</span></label>
                <input type="date" name="visit_date" class="form-input" value="{{ old('visit_date', now()->addDay()->format('Y-m-d')) }}" min="{{ now()->format('Y-m-d') }}" required>
                @error('visit_date') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Pilih Jadwal <span class="required">*</span></label>
                <div id="scheduleOptions">
                    <p style="font-size: 13px; color: var(--color-gray-500);">Pilih dokter terlebih dahulu untuk melihat jadwal yang tersedia.</p>
                </div>
                <input type="hidden" name="schedule" id="selectedSchedule" value="{{ old('schedule') }}">
                @error('schedule') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Nomor Surat Rujukan</label>
                <input type="text" name="referral_number" class="form-input" value="{{ old('referral_number') }}" placeholder="Opsional — jika ada rujukan dari faskes lain">
            </div>

            <div style="display: flex; gap: 10px; margin-top: 20px;">
                <button type="button" class="btn btn-outline btn-block" onclick="goToStep(1)">Kembali</button>
                <button type="button" class="btn btn-primary btn-block" onclick="goToStep(3)">Lanjutkan</button>
            </div>
        </div>

        {{-- ══════════════════════════════════════════
           STEP 3: Asuransi & Penjamin
           ══════════════════════════════════════════ --}}
        <div class="form-step" id="step-3" style="padding: 16px; display: none;">
            <h2 style="font-size: 18px; font-weight: 700; margin-bottom: 6px; color: var(--color-gray-900);">Asuransi &amp; Penjamin</h2>
            <p style="font-size: 13px; color: var(--color-gray-500); margin-bottom: 20px;">Pilih jenis penjamin biaya layanan kesehatan Anda.</p>

            <div class="form-group">
                <label class="option-card" onclick="selectPaymentType('umum')">
                    <input type="radio" name="patient_type" value="umum" {{ old('patient_type', 'umum') === 'umum' ? 'checked' : '' }} required>
                    <div class="option-radio"></div>
                    <div class="option-content">
                        <h4>Umum / Mandiri</h4>
                        <p>Membayar biaya layanan secara mandiri</p>
                    </div>
                </label>

                <label class="option-card" onclick="selectPaymentType('asuransi')">
                    <input type="radio" name="patient_type" value="asuransi" {{ old('patient_type') === 'asuransi' ? 'checked' : '' }}>
                    <div class="option-radio"></div>
                    <div class="option-content">
                        <h4>Asuransi Swasta</h4>
                        <p>Menggunakan asuransi kesehatan swasta</p>
                    </div>
                </label>
                <label class="option-card" onclick="selectPaymentType('perusahaan')">
                    <input type="radio" name="patient_type" value="perusahaan" {{ old('patient_type') === 'perusahaan' ? 'checked' : '' }}>
                    <div class="option-radio"></div>
                    <div class="option-content">
                        <h4>Penjamin Perusahaan</h4>
                        <p>Dijamin oleh perusahaan / institusi</p>
                    </div>
                </label>
            </div>

            {{-- Umum notice --}}
            <div id="umumNotice" style="background: var(--color-success-light); border: 1px solid #a7f3d0; border-radius: 10px; padding: 14px; margin-bottom: 16px;">
                <p style="font-size: 13px; color: #047857; margin: 0;">
                    <strong>Pasien Umum / Mandiri</strong> — Anda akan membayar biaya layanan secara mandiri tanpa menggunakan asuransi atau penjamin. Pastikan membawa identitas diri yang berlaku.
                </p>
            </div>

            {{-- Insurance/Penjamin fields (shown for asuransi, perusahaan) --}}
            <div id="insuranceFields" style="display: none;">
                <div class="form-group">
                    <label class="form-label">Nama Asuransi / Penjamin <span class="required">*</span></label>
                    <input type="text" name="insurance_name" class="form-input" value="{{ old('insurance_name') }}" id="insuranceNameInput">
                    @error('insurance_name') <div class="form-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Nama yang Terdaftar pada Asuransi <span class="required">*</span></label>
                    <input type="text" name="insurance_holder_name" class="form-input" value="{{ old('insurance_holder_name') }}" placeholder="Nama pemegang polis / peserta">
                    <div class="form-help">Nama sesuai yang tercatat di kartu asuransi (bisa berbeda dengan nama pasien)</div>
                </div>

                <div class="form-group">
                    <label class="form-label">Nomor Peserta / Nomor Polis <span class="required">*</span></label>
                    <input type="text" name="insurance_number" class="form-input" value="{{ old('insurance_number') }}">
                </div>

                <div class="form-group" id="referralField">
                    <label class="form-label">Nomor Rujukan / Surat Jaminan</label>
                    <input type="text" name="insurance_referral" class="form-input" value="{{ old('insurance_referral') }}" placeholder="Opsional">
                    <div class="form-help">Diperlukan untuk sebagian jenis penjamin. Kosongkan jika tidak ada.</div>
                </div>

                <div class="notice-banner info" style="margin: 0 0 16px;">
                    Kelengkapan kepesertaan dan klaim asuransi akan diverifikasi oleh petugas pendaftaran. Pastikan membawa dokumen pendukung saat kunjungan.
                </div>
            </div>

            <div style="display: flex; gap: 10px; margin-top: 20px;">
                <button type="button" class="btn btn-outline btn-block" onclick="goToStep(2)">Kembali</button>
                <button type="button" class="btn btn-primary btn-block" onclick="goToStep(4)">Lanjutkan</button>
            </div>
        </div>

        {{-- ══════════════════════════════════════════
           STEP 4: Konfirmasi
           ══════════════════════════════════════════ --}}
        <div class="form-step" id="step-4" style="padding: 16px; display: none;">
            <h2 style="font-size: 18px; font-weight: 700; margin-bottom: 6px; color: var(--color-gray-900);">Konfirmasi Data</h2>
            <p style="font-size: 13px; color: var(--color-gray-500); margin-bottom: 20px;">Periksa kembali data pendaftaran Anda sebelum mengirim.</p>

            <div style="background: var(--color-gray-50); border: 1px solid var(--color-gray-200); border-radius: 14px; padding: 16px; margin-bottom: 20px;">
                <div id="confirmationSummary">
                    {{-- Filled by JavaScript --}}
                </div>
            </div>

            <div class="form-group">
                <label class="checkbox-label">
                    <input type="checkbox" name="consent" id="consentCheckbox" required>
                    <span>Saya menyatakan bahwa data yang saya isi adalah benar dan saya menyetujui penggunaan data ini untuk proses pendaftaran di RS Santa Anna sesuai kebijakan rumah sakit.</span>
                </label>
            </div>

            <div class="notice-banner" style="margin: 0 0 16px;">
                Data pendaftaran ini merupakan data sementara dan belum terhubung ke sistem SIMRS RS Santa Anna.
            </div>

            <div style="display: flex; gap: 10px;">
                <button type="button" class="btn btn-outline btn-block" onclick="goToStep(3)">Kembali</button>
                <button type="submit" class="btn btn-primary btn-block" id="submitBtn" disabled>Kirim Pendaftaran</button>
            </div>
        </div>
    </form>

    @push('scripts')
    <script>
        let currentStep = 1;
        let patientStatus = 'baru';

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
                if (field.type === 'checkbox') {
                    if (!field.checked) { valid = false; }
                    return;
                }
                if (field.type === 'radio') {
                    const name = field.name;
                    const checked = stepEl.querySelector(`input[name="${name}"]:checked`);
                    if (!checked) { valid = false; }
                    return;
                }
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

        function selectPatientStatus(status) {
            patientStatus = status;
            const lamaSection = document.getElementById('patientLamaSection');
            const baruSection = document.getElementById('patientBaruSection');
            const lamaCard = document.getElementById('patientStatusLama');
            const baruCard = document.getElementById('patientStatusBaru');

            if (status === 'lama') {
                lamaSection.style.display = 'block';
                baruSection.style.display = 'none';
                lamaCard.classList.add('selected');
                baruCard.classList.remove('selected');
                // Disable required on baru fields
                baruSection.querySelectorAll('[required]').forEach(f => f.removeAttribute('required'));
            } else {
                lamaSection.style.display = 'none';
                baruSection.style.display = 'block';
                baruCard.classList.add('selected');
                lamaCard.classList.remove('selected');
                // Re-enable required
                baruSection.querySelectorAll('input[name="full_name"], input[name="nik"], input[name="phone"], input[name="birth_date"], select[name="gender"], textarea[name="address"]').forEach(f => f.setAttribute('required', 'required'));
            }
        }

        function searchPatient() {
            const mr = document.getElementById('mrInput').value.trim();
            if (!mr) { document.getElementById('mrInput').focus(); return; }

            const btn = document.getElementById('searchMrBtn');
            btn.disabled = true;
            btn.innerHTML = '<span>Mencari...</span>';

            // Simulate API call - in production this calls the backend
            setTimeout(() => {
                btn.disabled = false;
                btn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:16px;height:16px;"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg> Cari';

                // Mock: if MR starts with RM, show found
                if (mr.toUpperCase().startsWith('RM')) {
                    document.getElementById('patientDataResult').style.display = 'block';
                    document.getElementById('patientNotFound').style.display = 'none';
                    document.getElementById('patientDataContent').innerHTML = `
                        <div class="confirmation-row"><span class="label">No. RM</span><span class="value">${mr}</span></div>
                        <div class="confirmation-row"><span class="label">Nama</span><span class="value">Data pasien (dari backend)</span></div>
                        <div style="font-size: 12px; color: var(--color-gray-500); margin-top: 8px; font-style: italic;">
                            Data pasien akan ditampilkan setelah diverifikasi oleh sistem.
                        </div>
                    `;
                } else {
                    document.getElementById('patientDataResult').style.display = 'none';
                    document.getElementById('patientNotFound').style.display = 'block';
                }
            }, 800);
        }

        function selectPaymentType(type) {
            const insuranceFields = document.getElementById('insuranceFields');
            const umumNotice = document.getElementById('umumNotice');
            const cards = document.querySelectorAll('#step-3 .option-card');

            cards.forEach(card => {
                const radio = card.querySelector('input[type="radio"]');
                if (radio && radio.checked) {
                    card.classList.add('selected');
                } else {
                    card.classList.remove('selected');
                }
            });

            // Small delay so radio state updates
            setTimeout(() => {
                cards.forEach(card => {
                    const radio = card.querySelector('input[type="radio"]');
                    if (radio && radio.checked) {
                        card.classList.add('selected');
                    } else {
                        card.classList.remove('selected');
                    }
                });
            }, 10);

            if (type === 'umum') {
                insuranceFields.style.display = 'none';
                umumNotice.style.display = 'block';
                insuranceFields.querySelectorAll('input').forEach(f => f.removeAttribute('required'));
            } else {
                insuranceFields.style.display = 'block';
                umumNotice.style.display = 'none';
                document.getElementById('insuranceNameInput').setAttribute('required', 'required');
            }
        }

        function loadDoctors(polyclinicId) {
            const doctorSelect = document.getElementById('doctorSelect');
            const options = doctorSelect.querySelectorAll('option[data-polyclinic]');

            options.forEach(option => {
                if (!polyclinicId || option.dataset.polyclinic === polyclinicId) {
                    option.style.display = '';
                } else {
                    option.style.display = 'none';
                    if (option.selected) option.selected = false;
                }
            });

            doctorSelect.value = '';
            updateScheduleOptions();
        }

        function updateScheduleOptions() {
            const doctorSelect = document.getElementById('doctorSelect');
            const selectedOption = doctorSelect.options[doctorSelect.selectedIndex];
            const scheduleContainer = document.getElementById('scheduleOptions');

            if (!selectedOption || !selectedOption.dataset.schedules) {
                scheduleContainer.innerHTML = '<p style="font-size: 13px; color: var(--color-gray-500);">Pilih dokter terlebih dahulu untuk melihat jadwal yang tersedia.</p>';
                return;
            }

            const schedules = JSON.parse(selectedOption.dataset.schedules);
            let html = '';

            schedules.forEach(schedule => {
                const val = schedule.day + ' ' + schedule.time;
                html += `
                    <label class="option-card" onclick="selectSchedule('${val}', this)">
                        <input type="radio" name="schedule_option" value="${val}">
                        <div class="option-radio"></div>
                        <div class="option-content">
                            <h4>${schedule.day}</h4>
                            <p>${schedule.time}</p>
                        </div>
                    </label>
                `;
            });

            scheduleContainer.innerHTML = html;
        }

        function selectSchedule(value, element) {
            document.getElementById('selectedSchedule').value = value;
            document.querySelectorAll('#scheduleOptions .option-card').forEach(card => {
                card.classList.remove('selected');
            });
            element.classList.add('selected');
        }

        function buildConfirmationSummary() {
            const form = document.getElementById('registrationForm');
            const data = new FormData(form);

            const poliSelect = document.getElementById('polyclinicSelect');
            const doctorSelect = document.getElementById('doctorSelect');

            const poliName = poliSelect.options[poliSelect.selectedIndex]?.text || '-';
            const doctorName = doctorSelect.options[doctorSelect.selectedIndex]?.text || '-';

            const patientTypeMap = {
                'umum': 'Umum / Mandiri',

                'asuransi': 'Asuransi Swasta',
                'perusahaan': 'Penjamin Perusahaan'
            };
            const patientType = patientTypeMap[data.get('patient_type')] || '-';

            let rows = [];

            // Patient status
            rows.push(['Status Pasien', patientStatus === 'lama' ? 'Pasien Lama' : 'Pasien Baru']);
            if (patientStatus === 'lama') {
                rows.push(['No. Rekam Medis', data.get('medical_record_number') || '-']);
            }

            rows.push(['Nama Lengkap', data.get('full_name') || '-']);
            rows.push(['NIK', data.get('nik') || '-']);
            rows.push(['No. HP', data.get('phone') || '-']);
            rows.push(['Jenis Kelamin', data.get('gender') === 'L' ? 'Laki-laki' : data.get('gender') === 'P' ? 'Perempuan' : '-']);
            rows.push(['Jenis Layanan', 'Rawat Jalan']);
            rows.push(['Poliklinik', poliName]);
            rows.push(['Dokter', doctorName]);
            rows.push(['Tanggal Kunjungan', data.get('visit_date') || '-']);
            rows.push(['Jadwal', data.get('schedule') || '-']);
            rows.push(['Jenis Penjamin', patientType]);

            if (data.get('patient_type') !== 'umum') {
                rows.push(['Nama Asuransi/Penjamin', data.get('insurance_name') || '-']);
                rows.push(['Nama Terdaftar', data.get('insurance_holder_name') || '-']);
                rows.push(['No. Peserta/Polis', data.get('insurance_number') || '-']);
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
            selectPatientStatus('baru');

            // Set initial payment type
            const checkedType = document.querySelector('input[name="patient_type"]:checked');
            if (checkedType) {
                selectPaymentType(checkedType.value);
            } else {
                selectPaymentType('umum');
            }

            // Filter doctors by pre-selected polyclinic
            const poliSelect = document.getElementById('polyclinicSelect');
            if (poliSelect.value) {
                loadDoctors(poliSelect.value);
            }

            // Listen for doctor change
            document.getElementById('doctorSelect').addEventListener('change', updateScheduleOptions);

            // Consent checkbox enables submit
            document.getElementById('consentCheckbox').addEventListener('change', function() {
                document.getElementById('submitBtn').disabled = !this.checked;
            });
        });
    </script>
    @endpush
</div>
@endsection
