<?php

namespace App\Http\Controllers;

use App\Models\Follow;
use App\Models\Story;
use App\Models\StoryView;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StoryController extends Controller
{
    private $story;

    public function __construct(Story $story)
    {
        $this->story = $story;
    }
    public function index()
    {
        $user = Auth::user();
    
        // フォローしているユーザーのID取得（自分も含める）
        $followingIds = $user->followings->pluck('id')->push($user->id);
    
        // フォローしてる人のストーリーだけ取得（期限内のもの）
        $stories = Story::whereIn('user_id', $followingIds)
                        ->where('expires_at', '>', now())
                        ->with('user')
                        ->latest()
                        ->get()
                        ->groupBy('user_id');
    
        // 各ユーザーごとに「最初のストーリー」が既読かどうかを判定してフラグを付ける
        foreach ($stories as $userId => $userStories) {
            $firstStory = $userStories->first();
            $firstStory->is_read = $firstStory->views()
                ->where('viewer_id', $user->id)
                ->exists();
        }
    
        return view('story', ['groupedStories' => $stories]);
    }
    

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'nullable|image',
            'text' => 'nullable|string|max:255',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('stories', 'public');
        }

        Story::create([
            'user_id' => Auth::id(),
            'image_path' => $imagePath,
            'text' => $request->text,
            'expires_at' => now()->addHours(24),
        ]);

        return redirect()->back();
    }

    public function view(Story $story)
    {
        $userId = Auth::id();
    
        // すでに見た記録がなければ保存
        if (!StoryView::where('story_id', $story->id)->where('user_id', $userId)->exists()) {
            StoryView::create([
                'story_id' => $story->id,
                'user_id' => $userId,
            ]);
        }
    
        return response()->json(['status' => 'view recorded']);
    }
}
