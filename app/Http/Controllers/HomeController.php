<?php

namespace App\Http\Controllers;

use App\Models\Advertise;
use App\Models\Post;
use App\Models\User;
use App\Models\Story;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    private $post;
    private $user;

    public function __construct(Post $post, User $user){
        $this->post = $post;
        $this->user = $user;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request){
        $categoryIds = $request->input('categories', []);
        [$home_posts, $ads] = $this->getHomePosts($categoryIds);
        $suggested_users = $this->getSuggestedUser();
        $categories = Category::all();

        $followed_user_ids = Auth::user()->following()->pluck('following_id')->toArray();
        $followed_user_ids[] = Auth::id(); // 自分自身も含める
    
        // ✅ フォローしている人と自分のストーリーのみ取得
        $stories = Story::active()
            ->whereIn('user_id', $followed_user_ids)
            ->with(['user', 'views'])
            ->latest()
            ->get();
    
        $groupedStories = $stories->groupBy('user_id');
    
        return view('users.home')
            ->with('home_posts', $home_posts)
            ->with('suggested_users', $suggested_users)
            ->with('stories', $stories)
            ->with('ads', $ads)
            ->with('groupedStories', $groupedStories)
            ->with('categories', $categories)
            ->with('categoryIds', $categoryIds);
    }


    public function getHomePosts($categoryIds = []){
        $user = Auth::user();

        // ユーザーの興味に基づいた広告取得
        $ads = Advertise::whereHas('interests', function ($query) use ($user) {
            $query->whereIn('interests.id', $user->interests->pluck('id'));
        })->get();

        // 投稿取得（コメント数も含む）
        $all_posts = $this->post->with(['user', 'categoryPost'])->withCount('comments')->latest()->get();

        // 投稿をフィルター
        $home_posts = $all_posts->filter(function ($post) use ($user, $categoryIds) {
            $isMyPostOrFollowed = $post->user->isFollowed() || $post->user->id === $user->id;

            if (!$isMyPostOrFollowed) {
                return false;
            }

            if (empty($categoryIds)) {
                return true;
            }

            $postCategoryIds = $post->categoryPost->pluck('category_id')->toArray();
            return !empty(array_intersect($postCategoryIds, $categoryIds));
        });

        return [$home_posts->values(), $ads]; // → コレクションのキーをリセットして返す
    }



    public function getSuggestedUser(){
        $all_users = $this->user->all()->except(Auth::user()->id);
        $suggested_users = [];

        

        foreach ($all_users as $user) {
            if(!$user->isFollowed()){
                $suggested_users[] = $user;
            }
        }
        return collect($suggested_users)->take($limit = 5);
    }

    //New(RIKO)//
    public function suggestions(){
        $all_users = $this->user->all()->except(Auth::user()->id);
        $suggested_users = [];
        foreach ($all_users as $user) {
            if(!$user->isFollowed()){
                $suggested_users[] = $user;
            }
        }
        return view('users.modals.suggestions')->with('suggested_users', collect($suggested_users));
    }

     //New(RIKO)//
    public function search(Request $request){
        $users = $this->user->where('name', 'like', '%' . $request->search . '%')->where('id', '!=', Auth::user()->id)->get();
        return view('users.search')->with('users', $users)->with('search', $request->search);
    }

    public function searchUsers(Request $request)
    {
        $search = $request->search_users;

        $all_users = User::where('id', '!=', Auth::id())
            ->where('name', 'like', "%{$search}%")
            ->paginate(10) 
            ->appends(['search_users' => $search]);


        return view('admin.users.search', compact('all_users', 'search'));
    }

    public function searchPosts(Request $request){
        $search = $request->search_posts;
        $query = Post::query();

        $isUncategorized = strtolower(trim($search)) === 'uncategorized';

        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                // Post ID
                $q->where('id', 'like', "%{$search}%")
                // Category name (through categoryPost -> category)
                ->orWhereHas('categoryPost', function($q2) use ($search) {
                    $q2->whereHas('category', function($q3) use ($search) {
                        $q3->where('name', 'like', "%{$search}%");
                    });
                })
                  // User name
                ->orWhereHas('user', function($q4) use ($search) {
                    $q4->where('name', 'like', "%{$search}%");
                });

            });
            if (stripos('uncategorized', $search) !== false || stripos($search, 'uncategorized') !== false) {
                $query->orWhereDoesntHave('categoryPost');
            }
        }

        $all_posts = $query->with(['categoryPost.category', 'user'])
                           ->orderByDesc('created_at')
                           ->paginate(5)
                           ->appends(['search_posts' => $search]);
    
        return view('admin.posts.search', compact('all_posts', 'search'));
    }
}
