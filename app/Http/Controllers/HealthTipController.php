<?php

namespace App\Http\Controllers;

use App\Models\HealthTip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HealthTipController extends Controller
{
    public function index()
    {
        $tips = HealthTip::latest()->get();
        return view('health-tips', compact('tips'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'    => ['required', 'string', 'max:255'],
            'content'  => ['required', 'string'],
            'category' => ['required', 'string'],
            'image'    => ['nullable', 'image', 'max:2048'],
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('health-tips', 'public');
        }

        HealthTip::create([
            'user_id'  => Auth::id(),
            'title'    => $request->title,
            'content'  => $request->content,
            'category' => $request->category,
            'image'    => $imagePath,
        ]);

        return redirect()->back()->with('success', 'Health tip added successfully!');
    }
}