<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{
    // 1. READ: Barcha xodimlarni ro'yxatini ko'rsatish
    public function index()
    {
        $employees = Employee::all();
        return view('admin.brith.index', compact('employees'));
    }


    // 3. STORE: Yangi xodimni bazaga saqlash
    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'position' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('employees', 'public');
        }

        Employee::create($validated);

        return redirect()->route('employees.index')->with('success', 'Xodim muvaffaqiyatli qo\'shildi!');
    }



    // 6. UPDATE: Xodim ma'lumotlarini yangilash
    public function update(Request $request, Employee $employee)
    {

        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'position' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        // Agar yangi rasm yuklansa
        if ($request->hasFile('photo')) {
            // Eskisini o'chirish
            if ($employee->photo) {
                Storage::disk('public')->delete($employee->photo);
            }
            $validated['photo'] = $request->file('photo')->store('employees', 'public');
        }

        $employee->update($validated);

        return redirect()->route('employees.index')->with('success', 'Xodim ma\'lumotlari yangilandi!');
    }

    // 7. DELETE: Xodimni o'chirish
    public function destroy(Employee $employee)
    {
        // Xodimning rasmini xotiradan o'chirish
        if ($employee->photo) {
            Storage::disk('public')->delete($employee->photo);
        }

        $employee->delete();

        return redirect()->route('employees.index')->with('success', 'Xodim o\'chirildi!');
    }
}
