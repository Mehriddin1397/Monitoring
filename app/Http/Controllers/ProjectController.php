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
        $projects = Project::with('participants')->get();

        // Qatnashuvchilarni ajratib olish



        return view('admin.projectt.index', compact('projects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'name_pul' =>  'required|array|min:1',
            'name_pul.*'=> 'required|string|max:255',
            'name_free'=> 'required|array|min:1',
            'name_free.*' => 'required|string|max:255',
            'pro_bos_name' =>'required',
            'tel_number' => 'required',
            'job' => 'required',
            'file_buyruq' => 'required|file|mimes:pdf,doc,docx',
            'file_qushimcha' => 'required|file|mimes:pdf,doc,docx',

        ]);

        $file_buyruq = $request->file('file_buyruq')->store('project', 'public');
        $file_qushimcha = $request->file('file_qushimcha')->store('project', 'public');

        $project = Project::create([
            'name' => $request->name,
            'file_buyruq'=>$file_buyruq,
            'file_qushimcha'=>$file_qushimcha,
            'pro_bos_name' => $request->pro_bos_name,
            'tel_number' => $request->tel_number,
            'job' => $request->job,
        ]);

        // Pullik qatnashuvchilarni saqlash
        foreach ($request->name_pul as $participantName) {
            Participant::create([
                'name' => $participantName,
                'project_id' => $project->id,
                'type' => 'pul', // Qo'shimcha ma'lumot sifatida pullik yoki tekin ekanligini saqlash
            ]);
        }

        // Bepul qatnashuvchilarni saqlash
        foreach ($request->name_free as $participantName) {
            Participant::create([
                'name' => $participantName,
                'project_id' => $project->id,
                'type' => 'free', // Qo'shimcha ma'lumot sifatida
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
            'name' => 'required|string|max:255',
            'name_pul' => 'required|array|min:1',
            'name_pul.*' => 'required|string|max:255',
            'name_free' => 'required|array|min:1',
            'name_free.*' => 'required|string|max:255',
            'pro_bos_name' => 'required|string|max:255',
            'tel_number' => 'required|string|max:15',
            'job' => 'required|string|max:255',
            'file_buyruq' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            'file_qushimcha' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
        ]);

        // Loyiha ma'lumotlarini olish
        $project = Project::findOrFail($id);

        // Eski fayllarni o‘chirish va yangilarini yuklash
        if ($request->hasFile('file_buyruq')) {
            Storage::delete($project->file_buyruq);
            $project->file_buyruq = $request->file('file_buyruq')->store('documents');
        }

        if ($request->hasFile('file_qushimcha')) {
            Storage::delete($project->file_qushimcha);
            $project->file_qushimcha = $request->file('file_qushimcha')->store('documents');
        }

        // Loyiha ma'lumotlarini yangilash
        $project->update([
            'name' => $request->name,
            'pro_bos_name' => $request->pro_bos_name,
            'tel_number' => $request->tel_number,
            'job' => $request->job,
            'file_buyruq' => $project->file_buyruq,
            'file_qushimcha' => $project->file_qushimcha,
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

        foreach ($request->name_free as $participantName) {
            Participant::create([
                'name' => $participantName,
                'project_id' => $project->id,
                'type' => 'free',
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

        // Qidiruv natijalarini olish
        $projects = Project::where('pro_bos_name', 'LIKE', "%{$query}%")
            ->orWhereHas('participants', function ($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%");
            })
            ->with('participants')
            ->get();

        return view('admin.projectt.search', compact('projects','query'));
    }
}
