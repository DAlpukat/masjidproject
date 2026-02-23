<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TempatLayanan;

class AdminController extends Controller
{
    public function index()
    {
        $tempatLayanans = TempatLayanan::with('users')
            ->withCount(['users as members_count' => function ($query) {
                $query->where('is_admin', 0)
                      ->where('is_superadmin', 0);
            }])
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('admin.dashboard', compact('tempatLayanans'));
    }
}