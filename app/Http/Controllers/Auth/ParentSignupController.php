<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class ParentSignupController extends Controller
{
    /**
     * Display the parent signup page (email + parent code).
     */
    public function create(): Response
    {
        return Inertia::render('Auth/ParentSignup');
    }
}
