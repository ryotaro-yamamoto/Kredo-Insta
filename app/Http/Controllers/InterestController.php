<?php

namespace App\Http\Controllers;

use App\Models\Interest;
use Illuminate\Http\Request;

class InterestController extends Controller
{
    public function index()
    {
        $interests = Interest::all();
        return view('interests.index', compact('interests'));
    }

    public function create()
    {
        return view('interests.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50',
        ]);

        Interest::create($request->only('name'));

        return redirect()->route('interests.index');
    }
}