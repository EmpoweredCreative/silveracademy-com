<?php

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Marketing/Home');
    }

    public function about(): Response
    {
        return Inertia::render('Marketing/About');
    }

    public function admissions(): Response
    {
        return Inertia::render('Marketing/Admissions');
    }

    public function services(): Response
    {
        return Inertia::render('Marketing/Services');
    }

    public function contact(): Response
    {
        return Inertia::render('Marketing/Contact');
    }

    public function ganeinu(): Response
    {
        return Inertia::render('Marketing/Programs/Ganeinu');
    }

    public function kindergarten(): Response
    {
        return Inertia::render('Marketing/Programs/Kindergarten');
    }

    public function lowerSchool(): Response
    {
        return Inertia::render('Marketing/Programs/LowerSchool');
    }

    public function upperSchool(): Response
    {
        return Inertia::render('Marketing/Programs/UpperSchool');
    }

    public function afterSchool(): Response
    {
        return Inertia::render('Marketing/Programs/AfterSchool');
    }

    public function parentCircle(): Response
    {
        return Inertia::render('Marketing/Programs/ParentCircle');
    }

    public function getInvolved(): Response
    {
        return Inertia::render('Marketing/GetInvolved/Index');
    }

    public function donate(): Response
    {
        return Inertia::render('Marketing/GetInvolved/Donate');
    }

    public function eitcCorporate(): Response
    {
        return Inertia::render('Marketing/GetInvolved/EITCCorporate');
    }

    public function eitcIndividual(): Response
    {
        return Inertia::render('Marketing/GetInvolved/EITCIndividual');
    }

    public function lifeAndLegacy(): Response
    {
        return Inertia::render('Marketing/GetInvolved/LifeAndLegacy');
    }

    public function fundraisers(): Response
    {
        return Inertia::render('Marketing/GetInvolved/Fundraisers');
    }
}

