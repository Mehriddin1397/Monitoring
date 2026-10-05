<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmployeeWork;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EmployeeWorkController extends Controller
{
    public function index()
    {
        $works = EmployeeWork::with('employee')->latest()->get();
        $employees = Employee::orderBy('full_name')->get();
        return view('admin.brith.works', compact('works', 'employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'type' => 'required|string|in:poem,story,scientific,quote',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'layout_theme' => 'required|string|in:lyric,book,academic,card',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg|max:10240',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')->store('employee_works', 'public');
        }

        EmployeeWork::create($validated);

        return redirect()->route('employee-works.index')->with('success', 'Ижодий иш муваффақиятли қўшилди!');
    }

    public function update(Request $request, EmployeeWork $employeeWork)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'type' => 'required|string|in:poem,story,scientific,quote',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'layout_theme' => 'required|string|in:lyric,book,academic,card',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg|max:10240',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        if ($request->hasFile('cover_image')) {
            if ($employeeWork->cover_image) {
                Storage::disk('public')->delete($employeeWork->cover_image);
            }
            $validated['cover_image'] = $request->file('cover_image')->store('employee_works', 'public');
        }

        $employeeWork->update($validated);

        return redirect()->route('employee-works.index')->with('success', 'Ижодий иш муваффақиятли янгиланди!');
    }

    public function destroy(EmployeeWork $employeeWork)
    {
        if ($employeeWork->cover_image) {
            Storage::disk('public')->delete($employeeWork->cover_image);
        }

        $employeeWork->delete();

        return redirect()->route('employee-works.index')->with('success', 'Ижодий иш ўчирилди!');
    }

    public function toggle(EmployeeWork $employeeWork)
    {
        $employeeWork->update(['is_active' => !$employeeWork->is_active]);
        return response()->json(['success' => true, 'is_active' => $employeeWork->is_active]);
    }
}
