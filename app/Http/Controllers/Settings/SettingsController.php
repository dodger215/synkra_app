<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        return view('settings.index');
    }

    public function storeTheme(Request $request)
    {
        $request->validate(['theme' => 'required|in:light,dark,system']);
        session(['appearance' => $request->theme]);

        if ($request->wantsJson()) {
            return response()->json(['theme' => $request->theme]);
        }

        return back();
    }
}
