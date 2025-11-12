<?php

namespace App\Http\Controllers;

use App\Models\SystemSetting;
use Illuminate\Http\Request;

class SystemSettingsController extends Controller
{
    /**
     * Update system status (admin sets Online or Offline)
     */
    public function updateSystemStatus(Request $request)
    {
        $request->validate([
            'is_online' => 'required|boolean',
        ]);

        $setting = SystemSetting::first();

        if (!$setting) {
            return response()->json(['message' => 'System settings not found.'], 404);
        }

        $setting->update([
            'is_online' => $request->is_online,
        ]);

        return response()->json([
            'message' => 'System status updated successfully',
            'is_online' => $setting->is_online,
        ]);
    }

    /**
     * Show current system status
     */
    public function show()
    {
        $setting = SystemSetting::first();
        return response()->json(['is_online' => $setting->is_online]);
    }
}
