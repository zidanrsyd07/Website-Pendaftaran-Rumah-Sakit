<?php

namespace App\Http\Controllers;

use App\Services\SimrsServiceInterface;

class PolyclinicController extends Controller
{
    public function __construct(
        private SimrsServiceInterface $simrs
    ) {}

    public function index()
    {
        $polyclinics = $this->simrs->getPolyclinics();

        return view('pages.polyclinics', compact('polyclinics'));
    }

    public function show(int $id)
    {
        $polyclinic = $this->simrs->getPolyclinic($id);

        if (! $polyclinic) {
            abort(404);
        }

        $doctors = $this->simrs->getDoctorsByPolyclinic($id);

        return view('pages.polyclinic-detail', compact('polyclinic', 'doctors'));
    }
}
