<?php

namespace App\Http\Controllers\Api\Marketing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MarketingPlatformConnection;
use Illuminate\Support\Facades\Auth;

class ConnectionController extends Controller
{
    public function index()
    {
        $connections = MarketingPlatformConnection::with('platform')->where('tenant_id', Auth::user()->tenant_id)->get();
        return response()->json($connections);
    }
}
