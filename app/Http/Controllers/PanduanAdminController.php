<?php

namespace App\Http\Controllers;

class PanduanAdminController extends Controller
{
    /**
     * Display the admin guide page.
     */
    public function index()
    {
        return view('admin.panduan.index');
    }
}
