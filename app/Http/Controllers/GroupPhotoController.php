<?php

namespace App\Http\Controllers;

use App\Models\GroupPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GroupPhotoController extends Controller
{
    // READ: Guruh rasmlari ro'yxati va qo'shish formasi (bitta sahifada qilsa qulay)
    public function index()
    {
        $photos = GroupPhoto::latest()->get();
        return view('admin.brith.photo', compact('photos'));
    }


    // STORE: Yangi guruh rasmini saqlash
    public function store(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('group_photos', 'public');
            GroupPhoto::create(['image_path' => $path]);
        }

        return redirect()->route('group-photos.index')->with('success', 'Rasm muvaffaqiyatli yuklandi!');
    }

    // DELETE: Guruh rasmini o'chirish
    public function destroy(GroupPhoto $groupPhoto)
    {
        // Rasmni storage papkasidan o'chirish
        if ($groupPhoto->image_path) {
            Storage::disk('public')->delete($groupPhoto->image_path);
        }

        $groupPhoto->delete();

        return redirect()->route('group-photos.index')->with('success', 'Rasm o\'chirildi!');
    }
}
