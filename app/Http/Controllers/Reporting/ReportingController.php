<?php

namespace App\Http\Controllers\Reporting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReportingController extends Controller
{
    public function index()
    {
        return view('reporting.index');
    }

    public function dashboards()
    {
        return view('reporting.dashboards');
    }

    public function reports()
    {
        return view('reporting.reports');
    }

    public function kpi()
    {
        return view('reporting.kpi');
    }

    public function auditLogs()
    {
        return view('reporting.audit_logs');
    }
}
