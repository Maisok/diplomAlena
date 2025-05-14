<?php

namespace App\Http\Controllers;

use App\Models\Certificate;

class CertificateDisplayController extends Controller
{
    public function index()
    {
        $licenses = Certificate::where('type', 'license')->get();
        $certificates = Certificate::where('type', 'certificate')->get();

        return view('success', compact('licenses', 'certificates'));
    }
}