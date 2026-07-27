<?php

namespace App\Http\Controllers\Api\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TenantController extends Controller
{
    public function show()
    {
        return response()->json(Auth::user()->tenant);
    }

    public function update(Request $request)
    {
        $tenant = Auth::user()->tenant;
        $tenant->update($request->all());
        return response()->json($tenant);
    }
}
