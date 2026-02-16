<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TempatLayanan;

class AdminController extends Controller
{
    public function index()
    {
        $tempatLayanans = TempatLayanan::with('users')
                                        ->where('user_id', auth()->id())
                                        ->latest()
                                        ->get();

        return view('admin.dashboard', compact('tempatLayanans'));
    }
}