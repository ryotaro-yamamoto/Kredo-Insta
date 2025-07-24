<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Interest;

class NewInterestController extends Controller
{
    // 一覧表示
    public function index()
    {
        $interests = Interest::with('users')->orderBy('created_at', 'desc')->get();
        return view('admin.interests.index', compact('interests'));
    }

    // 作成画面
    public function create()
    {
        return view('admin.interests.create');
    }

    // 登録処理
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:interests,name',
        ]);

        Interest::create($validated);

        return redirect()->route('admin.interests')->with('success', 'Interest created successfully.');
    }

    // 更新処理
    public function update(Request $request, $id)
    {
        $interest = Interest::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:interests,name,' . $interest->id,
        ]);

        $interest->update($validated);

        return redirect()->route('admin.interests.index')->with('success', 'Interest updated successfully.');
    }

    // 削除処理
    public function destroy($id)
    {
        $interest = Interest::findOrFail($id);
        $interest->delete();

        return redirect()->route('admin.interests')->with('success', 'Interest deleted successfully.');
    }
}