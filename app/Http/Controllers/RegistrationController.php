<?php

namespace App\Http\Controllers;

use App\Services\SimrsServiceInterface;
use Illuminate\Http\Request;

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
            'patient_type' => 'required|in:umum,asuransi',
            'insurance_name' => 'required_if:patient_type,asuransi|nullable|string|max:255',
            // Rawat Jalan
            'polyclinic_id' => 'required_if:service_type,rawat_jalan|nullable|integer',
            'doctor_id' => 'required_if:service_type,rawat_jalan|nullable|integer',
            'visit_date' => 'required_if:service_type,rawat_jalan|nullable|date',
            'schedule' => 'required_if:service_type,rawat_jalan|nullable|string',
            'medical_record_number' => 'nullable|string|max:20',
            // Rawat Inap
            'room_class' => 'required_if:service_type,rawat_inap|nullable|string',
            'notes' => 'nullable|string|max:1000',
        ]);

        $result = $this->simrs->submitRegistration($validated);

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
