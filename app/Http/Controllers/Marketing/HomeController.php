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
}

