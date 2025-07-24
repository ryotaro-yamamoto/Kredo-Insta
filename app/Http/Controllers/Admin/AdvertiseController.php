<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Advertise;
use App\Models\Interest;
use App\Http\Controllers\Controller;

class AdvertiseController extends Controller
{
    public function index()
    {
        $ads = Advertise::with('interests')->get();
        return view('admin.advertises.index', compact('ads'));
    }

    public function create()
    {
        $interests = Interest::all();
        return view('admin.advertises.create', compact('interests'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'description' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'interests' => 'array|exists:interests,id',
        ]);

        // ✅ Base64に変換して保存
        $imageData = base64_encode(file_get_contents($request->file('image')));
        $mime = $request->file('image')->getMimeType();
        $base64Image = "data:$mime;base64,$imageData";

        $ad = Advertise::create([
            'description' => $request->description,
            'image' => $base64Image,
            'advertiser_id' => auth()->id(),
        ]);

        $ad->interests()->attach($request->interests);

        return redirect()->route('admin.advertises')->with('success', '広告を作成しました');
    }

    public function destroy($id)
    {
        $advertise = Advertise::findOrFail($id);
        $advertise->delete();

        return redirect()->route('admin.advertises')->with('success', '広告を削除しました');
    }

    public function edit($id)
    {
        $advertise = Advertise::with('interests')->findOrFail($id);
        $interests = Interest::all();
        return view('admin.advertises.edit', compact('advertise', 'interests'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'interests' => 'array|nullable',
        ]);

        $advertise = Advertise::findOrFail($id);
        $advertise->description = $request->description;

        if ($request->hasFile('image')) {
            // ✅ Base64に変換して保存（更新時）
            $imageData = base64_encode(file_get_contents($request->file('image')));
            $mime = $request->file('image')->getMimeType();
            $advertise->image = "data:$mime;base64,$imageData";
        }

        $advertise->save();

        $advertise->interests()->sync($request->interests ?? []);

        return redirect()->route('admin.advertises')->with('success', '広告を更新しました');
    }
}