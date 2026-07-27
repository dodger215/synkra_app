<?php

namespace App\Http\Controllers\Crm;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CustomerSegment;
use Illuminate\Support\Facades\Auth;

class SegmentController extends Controller
{
    public function index()
    {
        $tenantId = Auth::user()->tenant_id;
        $segments = CustomerSegment::where('tenant_id', $tenantId)->latest()->get();
        return view('crm.segments.index', compact('segments'));
    }

    public function create()
    {
        return view('crm.segments.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
        ]);

        CustomerSegment::create([
            'tenant_id' => Auth::user()->tenant_id,
            'name' => $request->name,
            'description' => $request->description,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('crm.segments.index')->with('success', 'Segment created successfully.');
    }

    public function show($id)
    {
        $tenantId = Auth::user()->tenant_id;
        $segment = CustomerSegment::where('tenant_id', $tenantId)->findOrFail($id);
        return view('crm.segments.show', compact('segment'));
    }

    public function edit($id)
    {
        $tenantId = Auth::user()->tenant_id;
        $segment = CustomerSegment::where('tenant_id', $tenantId)->findOrFail($id);
        return view('crm.segments.edit', compact('segment'));
    }

    public function update(Request $request, $id)
    {
        $tenantId = Auth::user()->tenant_id;
        $segment = CustomerSegment::where('tenant_id', $tenantId)->findOrFail($id);
        $segment->update($request->all());

        return redirect()->route('crm.segments.index')->with('success', 'Segment updated successfully.');
    }

    public function destroy($id)
    {
        $tenantId = Auth::user()->tenant_id;
        $segment = CustomerSegment::where('tenant_id', $tenantId)->findOrFail($id);
        $segment->delete();

        return redirect()->route('crm.segments.index')->with('success', 'Segment deleted.');
    }
}
