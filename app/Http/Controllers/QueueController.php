<?php

namespace App\Http\Controllers;

use App\Services\SimrsServiceInterface;
use Illuminate\Http\Request;

class QueueController extends Controller
{
    public function __construct(
        private SimrsServiceInterface $simrs
    ) {}

    public function index(Request $request)
    {
        $polyclinics = $this->simrs->getPolyclinics();
        $selectedPolyclinicId = $request->query('polyclinic_id');
        $selectedDoctorId = $request->query('doctor_id');
        $queues = [];

        if ($selectedPolyclinicId) {
            $queues = $this->simrs->getQueueStatus(
                (int) $selectedPolyclinicId,
                $selectedDoctorId ? (int) $selectedDoctorId : null
            );
        } else {
            // Tampilkan semua antrian
            foreach ($polyclinics as $poli) {
                $poliQueues = $this->simrs->getQueueStatus($poli['id']);
                $queues = array_merge($queues, $poliQueues);
            }
        }

        return view('pages.queue', compact('polyclinics', 'queues', 'selectedPolyclinicId', 'selectedDoctorId'));
    }
}
