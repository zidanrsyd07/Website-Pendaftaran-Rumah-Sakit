<?php

namespace App\Http\Controllers;

use App\Services\SimrsServiceInterface;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    public function __construct(
        private SimrsServiceInterface $simrs
    ) {}

    public function index(Request $request)
    {
        $filters = $request->only(['search', 'polyclinic_id', 'day']);
        $doctors = $this->simrs->getDoctors($filters);
        $polyclinics = $this->simrs->getPolyclinics();

        return view('pages.doctors', compact('doctors', 'polyclinics', 'filters'));
    }

    public function show(int $id)
    {
        $doctor = $this->simrs->getDoctor($id);

        if (! $doctor) {
            abort(404);
        }

        return view('pages.doctor-detail', compact('doctor'));
    }
}
