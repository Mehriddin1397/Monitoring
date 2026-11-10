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


    // STORE: Yangi guruh rasmini saqlash (bir vaqtda bitta yoki bir nechta rasm, 25MB gacha)
    public function store(Request $request)
    {
        $request->validate([
            'photos'   => 'nullable|array',
            'photos.*' => 'image|mimes:jpeg,png,jpg,gif,webp,jfif,avif,bmp,heic|max:25600',
            'photo'    => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,jfif,avif,bmp,heic|max:25600',
        ], [
            'photos.*.image' => 'Yuklangan fayl rasm formatida bo\'lishi kerak.',
            'photos.*.mimes' => 'Faqat rasm formatlari (jpeg, png, jpg, gif, webp, jfif, avif) qabul qilinadi.',
            'photos.*.max'   => 'Har bir rasm hajmi 25MB dan oshmasligi kerak.',
            'photo.image'    => 'Yuklangan fayl rasm formatida bo\'lishi kerak.',
            'photo.mimes'    => 'Faqat rasm formatlari (jpeg, png, jpg, gif, webp, jfif, avif) qabul qilinadi.',
            'photo.max'      => 'Rasm hajmi 25MB dan oshmasligi kerak.',
        ]);

        $uploadedCount = 0;

        // 1. Bir nechta rasm yuklangan bo'lsa
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $file) {
                if ($file->isValid()) {
                    $path = $file->store('group_photos', 'public');
                    GroupPhoto::create(['image_path' => $path]);
                    $uploadedCount++;
                }
            }
        }

        // 2. Bitta rasm yuklangan bo'lsa
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            if ($file->isValid()) {
                $path = $file->store('group_photos', 'public');
                GroupPhoto::create(['image_path' => $path]);
                $uploadedCount++;
            }
        }

        if ($uploadedCount > 0) {
            return redirect()->route('group-photos.index')
                ->with('success', "{$uploadedCount} ta guruh rasmi muvaffaqiyatli yuklandi!");
        }

        return redirect()->route('group-photos.index')
            ->with('error', 'Hech qanday rasm tanlanmadi yoki yuklashda xatolik yuz berdi.');
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
