<?php

namespace App\Http\Controllers;

use App\Models\QueueTicket;
use App\Models\Registration;
use App\Services\SimrsServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RegistrationController extends Controller
{
    public function __construct(
        private SimrsServiceInterface $simrs
    ) {}

    /**
     * Step 1: Pilih jenis layanan (Rawat Jalan / Rawat Inap)
     */
    public function selectType(Request $request)
    {
        $doctorId = $request->query('doctor_id');
        $polyclinicId = $request->query('polyclinic_id');

        $doctor = $doctorId ? $this->simrs->getDoctor((int) $doctorId) : null;
        $polyclinic = $polyclinicId ? $this->simrs->getPolyclinic((int) $polyclinicId) : null;

        return view('pages.registration.select-type', compact('doctor', 'polyclinic'));
    }

    /**
     * Form Rawat Jalan - Multi-step
     */
    public function outpatientForm(Request $request)
    {
        $polyclinics = $this->simrs->getPolyclinics();
        $doctors = $this->simrs->getDoctors();

        $prefill = [
            'doctor_id' => $request->query('doctor_id'),
            'polyclinic_id' => $request->query('polyclinic_id'),
        ];

        return view('pages.registration.outpatient', compact('polyclinics', 'doctors', 'prefill'));
    }

    /**
     * Form Rawat Inap
     */
    public function inpatientForm(Request $request)
    {
        return view('pages.registration.inpatient');
    }

    /**
     * Submit pendaftaran
     */
    public function submit(Request $request)
    {
        $validated = $request->validate([
            'service_type' => 'required|in:rawat_jalan,rawat_inap',
            'full_name' => 'required|string|max:255',
            'nik' => 'required|string|size:16',
            'phone' => 'required|string|max:15',
            'birth_date' => 'required|date',
            'gender' => 'required|in:L,P',
            'address' => 'required|string|max:500',
            'patient_status' => 'nullable|in:baru,lama',
            'patient_type' => 'required|in:umum,asuransi,perusahaan',
            'consent' => 'accepted',
            // Insurance / Penjamin fields
            'insurance_name' => 'required_unless:patient_type,umum|nullable|string|max:255',
            'insurance_number' => 'nullable|string|max:50',
            'insurance_holder_name' => 'nullable|string|max:255',
            'insurance_referral' => 'nullable|string|max:100',
            // Rawat Jalan
            'polyclinic_id' => 'required_if:service_type,rawat_jalan|nullable|integer',
            'doctor_id' => 'required_if:service_type,rawat_jalan|nullable|integer',
            'visit_date' => 'required_if:service_type,rawat_jalan|nullable|date',
            'schedule' => 'required_if:service_type,rawat_jalan|nullable|string',
            'medical_record_number' => 'nullable|string|max:20',
            'referral_number' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'emergency_contact' => 'nullable|string|max:100',
            'birth_place' => 'nullable|string|max:100',
            // Rawat Inap
            'room_class' => 'required_if:service_type,rawat_inap|nullable|string',
            'notes' => 'nullable|string|max:1000',
            'referring_doctor' => 'nullable|string|max:255',
            'guardian_contact' => 'nullable|string|max:100',
        ]);

        $result = DB::transaction(function () use ($validated) {
            $polyclinicName = 'Admisi Rawat Inap';
            $doctorName = $validated['referring_doctor'] ?? null;

            if (! empty($validated['polyclinic_id'])) {
                $poli = $this->simrs->getPolyclinic((int) $validated['polyclinic_id']);
                $polyclinicName = $poli['name'] ?? 'Poli Umum';
            }

            if (! empty($validated['doctor_id'])) {
                $doc = $this->simrs->getDoctor((int) $validated['doctor_id']);
                $doctorName = $doc['name'] ?? null;
            }

            $loketName = $validated['service_type'] === 'rawat_jalan' ? 'Loket Rawat Jalan' : 'Loket Rawat Inap';
            $regNumber = Registration::generateRegistrationNumber();
            $queueNumber = QueueTicket::generateQueueNumber($loketName);

            $patientType = in_array($validated['patient_type'], ['umum', 'bpjs', 'asuransi']) ? $validated['patient_type'] : 'asuransi';

            $registration = Registration::create([
                'user_id' => Auth::id(),
                'registration_number' => $regNumber,
                'patient_name' => $validated['full_name'],
                'nik' => $validated['nik'],
                'phone' => $validated['phone'],
                'service_type' => $validated['service_type'],
                'polyclinic' => $polyclinicName,
                'doctor_name' => $doctorName,
                'patient_type' => $patientType,
                'room_class' => $validated['room_class'] ?? null,
                'status' => 'pending',
                'visit_date' => $validated['visit_date'] ?? today(),
                'notes' => $validated['notes'] ?? null,
            ]);

            $ticket = QueueTicket::create([
                'registration_id' => $registration->id,
                'queue_number' => $queueNumber,
                'loket_name' => $loketName,
                'status' => 'waiting',
            ]);

            return [
                'success' => true,
                'registration_number' => $regNumber,
                'queue_number' => $queueNumber,
                'loket_name' => $loketName,
                'data' => $validated,
                'registration' => $registration,
                'ticket' => $ticket,
                'message' => 'Pendaftaran berhasil disimpan ke sistem RS Santa Anna.',
            ];
        });

        if ($result['success']) {
            // Enrich with readable data
            if (! empty($validated['polyclinic_id'])) {
                $result['polyclinic'] = $this->simrs->getPolyclinic((int) $validated['polyclinic_id']);
            }
            if (! empty($validated['doctor_id'])) {
                $result['doctor'] = $this->simrs->getDoctor((int) $validated['doctor_id']);
            }

            return view('pages.registration.confirmation', compact('result', 'validated'));
        }

        return back()->withErrors(['submit' => 'Pendaftaran gagal. Silakan coba lagi.'])->withInput();
    }
}
