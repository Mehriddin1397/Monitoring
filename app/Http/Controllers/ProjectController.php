<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if (in_array($user->role, ['boshliq', 'admin'])) {
            $projects = Project::with('participants')->get();
        } else {
            $projects = Project::with('participants')
                ->where('user_id', $user->id)
                ->get();
        }

        return view('admin.projectt.index', compact('projects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'name_pul' =>  'required|array|min:1',
            'name_pul.*'=> 'required|string|max:255',
            'pro_bos_name' =>'required',
            'manba' => 'string|nullable',
            'job' => 'string|nullable',
            'izoh' => 'string|nullable',
            'file_buyruq' => 'required|file|mimes:pdf,doc,docx',
            'file_qushimcha' => 'file|mimes:pdf,doc,docx',

        ]);



        $request->validate([
            'name' => 'required|string',
            'name_pul' =>  'required|array|min:1',
            'name_pul.*'=> 'required|string|max:255',
            'pro_bos_name' =>'required',
            'manba' => 'string|nullable',
            'job' => 'string|nullable',
            'izoh' => 'string|nullable',
            'file_buyruq' => 'required|file|mimes:pdf,doc,docx',
            'file_qushimcha' => 'nullable|file|mimes:pdf,doc,docx',
        ]);

// Asosiy faylni saqlash
        $file_buyruq = $request->file('file_buyruq')->store('project', 'public');

// Loyiha yaratish
        $project = Project::create([
            'name' => $request->name,
            'file_buyruq' => $file_buyruq,
            'pro_bos_name' => $request->pro_bos_name,
            'manba' => $request->manba,
            'izoh' => $request->izoh,
            'job' => $request->job,
            'user_id' => auth()->id(),
        ]);

// Pullik qatnashuvchilarni saqlash
        foreach ($request->name_pul as $participantName) {
            Participant::create([
                'name' => $participantName,
                'project_id' => $project->id,
                'type' => 'pul',
            ]);
        }

// Qo‘shimcha faylni tekshirish va saqlash
        if ($request->hasFile('file_qushimcha')) {
            $file_qushimcha = $request->file('file_qushimcha')->store('project', 'public');
            $project->update([
                'file_qushimcha' => $file_qushimcha
            ]);
        }



        return redirect()->route('projects.index');


    }

    public function show($id)
    {

    }

    public function update(Request $request, $id)
    {
        // Validayatsiya
        $request->validate([
            'name' => 'required|string',
            'name_pul' =>  'required|array|min:1',
            'name_pul.*'=> 'required|string|max:255',
            'pro_bos_name' =>'required',
            'manba' => 'string|nullable',
            'job' => 'string|nullable',
            'izoh' => 'string|nullable',
            'file_buyruq' => 'nullable|file|mimes:pdf,doc,docx',
            'file_qushimcha' => 'nullable|file|mimes:pdf,doc,docx',
        ]);


        // Loyiha ma'lumotlarini olish
        $project = Project::findOrFail($id);

        // Eski fayllarni o‘chirish va yangilarini yuklash
        if ($request->hasFile('file_buyruq')) {
            Storage::delete($project->file_buyruq);
            $project->file_buyruq = $request->file('file_buyruq')->store('documents');
        }

        if ($request->hasFile('file_qushimcha')) {
            if ($project->file_qushimcha) {
                Storage::delete($project->file_qushimcha);
            }
            $project->file_qushimcha = $request->file('file_qushimcha')->store('documents');
        }

        // Loyiha ma'lumotlarini yangilash
        $project->update([
            'name' => $request->name,
            'pro_bos_name' => $request->pro_bos_name,
            'job' => $request->job,
            'manba' => $request->manba,
            'izoh' => $request->izoh,
            'file_buyruq' => $project->file_buyruq,

        ]);

        // Eski qatnashuvchilarni o‘chirish
        $project->participants()->delete();

        // Yangi qatnashuvchilarni qo‘shish
        foreach ($request->name_pul as $participantName) {
            Participant::create([
                'name' => $participantName,
                'project_id' => $project->id,
                'type' => 'pul',
            ]);
        }

        if ($request->hasFile('file_qushimcha')) {
            $file_qushimcha = $request->file('file_qushimcha')->store('project', 'public');
            $project->update([
                'file_qushimcha' => $file_qushimcha
            ]);
        }

        return redirect()->route('projects.index');

    }

    public function destroy(Project $project)
    {
        $project->delete();
        return redirect()->route('projects.index');

    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $user = auth()->user();

        $projectsQuery = Project::with('participants');

        // Agar user boshliq yoki admin bo'lsa, barcha projectlar ichida qidirsin
        if ($user->role == 'boshliq' || $user->role == 'admin') {
            $projectsQuery->where(function ($q) use ($query) {
                $q->where('pro_bos_name', 'LIKE', "%{$query}%")
                    ->orWhereHas('participants', function ($q2) use ($query) {
                        $q2->where('name', 'LIKE', "%{$query}%");
                    });
            });
        } else {
            // Faqat o'zi yaratgan projectlar ichida qidirish
            $projectsQuery->where('user_id', $user->id)
                ->where(function ($q) use ($query) {
                    $q->where('pro_bos_name', 'LIKE', "%{$query}%")
                        ->orWhereHas('participants', function ($q2) use ($query) {
                            $q2->where('name', 'LIKE', "%{$query}%");
                        });
                });
        }

        $projects = $projectsQuery->get();

        return view('admin.projectt.search', compact('projects','query'));
    }
}
