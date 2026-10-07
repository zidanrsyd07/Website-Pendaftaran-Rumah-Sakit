<?php

namespace App\Http\Controllers;

use App\Services\SimrsServiceInterface;

class HomeController extends Controller
{
    public function __construct(
        private SimrsServiceInterface $simrs
    ) {}

    public function index()
    {
        $polyclinics = $this->simrs->getPolyclinics();

        return view('pages.home', compact('polyclinics'));
    }
}
