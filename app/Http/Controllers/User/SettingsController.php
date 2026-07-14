<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = auth()->user()->settings ?? [];
        return view('user.settings.index', compact('settings'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'email_notifications' => 'nullable|boolean',
            'sms_notifications' => 'nullable|boolean',
            'push_notifications' => 'nullable|boolean',
            'language' => 'sometimes|required|string',
            'timezone' => 'sometimes|required|string',
        ]);

        $user = auth()->user();
        $user->settings = array_merge($user->settings ?? [], $validated);
        $user->save();

        return back()->with('success', 'Pengaturan berhasil disimpan!');
    }
}
