<?php

namespace App\Http\Controllers;

class InstitutePortalController extends Controller
{
    /**
     * Show the standalone institute login view.
     */
    public function showLogin()
    {
        return view('institutes.login');
    }

    /**
     * Show the SPA shell for the institute home screen.
     */
    public function showHome()
    {
        return view('institutes.home');
    }
}
