<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Fee;
use App\Models\StudentProfile;
use Illuminate\Http\Request;

class FeeController extends Controller
{
    public function index()
    {
        $fees = Fee::with('studentProfile.user')->latest()->paginate(15);
        $totalOutstanding = Fee::where('status', 'unpaid')->sum('amount');
        $totalCollected = Fee::where('status', 'paid')->sum('amount');
        
        return view('admin.fees.index', compact('fees', 'totalOutstanding', 'totalCollected'));
    }

    public function create()
    {
        $students = StudentProfile::with('user')->get();
        return view('admin.fees.create', compact('students'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_profile_id' => 'required|exists:student_profiles,id',
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date',
            'status' => 'required|in:paid,unpaid',
        ]);

        Fee::create($validated);

        return redirect()->route('admin.fees.index')->with('success', 'Fee record created successfully.');
    }

    public function edit(Fee $fee)
    {
        $students = StudentProfile::with('user')->get();
        return view('admin.fees.edit', compact('fee', 'students'));
    }

    public function update(Request $request, Fee $fee)
    {
        $validated = $request->validate([
            'student_profile_id' => 'required|exists:student_profiles,id',
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date',
            'status' => 'required|in:paid,unpaid',
        ]);

        if ($validated['status'] === 'paid' && $fee->status !== 'paid') {
            $validated['paid_at'] = now();
            $validated['transaction_id'] = 'TXN_' . strtoupper(uniqid());
        }

        $fee->update($validated);

        return redirect()->route('admin.fees.index')->with('success', 'Fee record updated successfully.');
    }

    public function destroy(Fee $fee)
    {
        $fee->delete();
        return redirect()->route('admin.fees.index')->with('success', 'Fee record deleted successfully.');
    }
}
