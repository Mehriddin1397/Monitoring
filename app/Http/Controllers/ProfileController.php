<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
use App\Services\TurniketService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Foydalanuvchining shaxsiy profili
     */
    public function show(TurniketService $turniketService)
    {
        $user = Auth::user();
        $employee = $user->employee_profile;

        // 1. Articles and I-index score details
        $articles = $user->articles()
            ->with(['articleScore', 'category'])
            ->latest('id')
            ->get();

        $iIndexTotalScore = round((float) $articles->sum(function ($a) {
            return $a->articleScore ? (float) $a->articleScore->total_score : 0;
        }), 2);

        // 2. Turniket profile if employee has turniket_id
        $turniketProfile = null;
        if ($employee && !empty($employee->turniket_id)) {
            try {
                $tRes = $turniketService->getEmployee($employee->turniket_id);
                $turniketProfile = $tRes['data'] ?? null;
            } catch (\Exception $e) {
                // ignore turniket connection failure
            }
        }

        // 3. Assigned tasks count
        $assignedTasksCount = $user->assignedTasks()->count();
        $completedTasksCount = $user->assignedTasks()->where('tasks.status', 'bajarildi')->count();

        // 4. Birthday calculations
        $birthDate = $employee?->birth_date;
        $birthdayInfo = null;
        if (!empty($birthDate)) {
            try {
                $bDate = Carbon::parse($birthDate);
                $now = Carbon::now();
                $thisYearBday = $bDate->copy()->year($now->year);
                if ($thisYearBday->isPast() && !$thisYearBday->isToday()) {
                    $thisYearBday->addYear();
                }
                $daysRemaining = (int) $now->diffInDays($thisYearBday, false);

                $birthdayInfo = [
                    'formatted'      => $bDate->format('d.m.Y'),
                    'age'            => $bDate->age,
                    'is_today'       => $bDate->isBirthday(),
                    'days_remaining' => $daysRemaining,
                ];
            } catch (\Exception $e) {
                // ignore
            }
        }

        return view('admin.profile.show', compact(
            'user',
            'employee',
            'articles',
            'iIndexTotalScore',
            'turniketProfile',
            'assignedTasksCount',
            'completedTasksCount',
            'birthdayInfo'
        ));
    }
}
