<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function edit()
    {
        $setting = Setting::first() ?? new Setting();
        return view('admin.settings.edit', compact('setting'));
    }

    public function update(Request $request)
    {
        $setting = Setting::first() ?? new Setting();

        $validated = $request->validate([
            'site_name' => 'required|string|max:255',
            'email'     => 'nullable|email|max:255',
            'phone'     => 'nullable|string|max:255',
            'address'   => 'nullable|string',
            'logo'      => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048'
        ]);

        // Handle logo removal
        if ($request->boolean('remove_logo') && $setting->logo) {
            Storage::disk('public')->delete($setting->logo);
            $validated['logo'] = null;
        }

        // Handle new logo upload
        if ($request->hasFile('logo')) {
            if ($setting->logo) {
                Storage::disk('public')->delete($setting->logo);
            }
            $validated['logo'] = $request->file('logo')->store('settings', 'public');
        }

        if ($setting->exists) {
            $setting->update($validated);
        } else {
            Setting::create($validated);
        }

        \Illuminate\Support\Facades\Cache::forget('site_setting');

        return redirect()->back()->with('success', 'Paramètres mis à jour.');
    }
}
