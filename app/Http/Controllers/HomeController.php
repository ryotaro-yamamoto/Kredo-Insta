<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
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
        $home_posts = $this->getHomePosts($categoryIds);
        $suggested_users = $this->getSuggestedUser();
        $categories = Category::all();

        return view('users.home')
                ->with('home_posts', $home_posts)
                ->with('suggested_users', $suggested_users)
                ->with('categories', $categories)
                ->with('categoryIds', $categoryIds);
    }

    public function getHomePosts($categoryIds = []){
        $all_posts = $this->post->withCount('comments')->latest()->get();
        $home_posts = [];

        foreach ($all_posts as $post) {
            $isMyPostOrFollowed = $post->user->isFollowed() || $post->user->id === Auth::id();

            if (!$isMyPostOrFollowed) {
                continue;
            }

            if (empty($categoryIds)) {
                $home_posts[] = $post;
                continue;
            }

            $postCategoryIds = $post->categoryPost->pluck('category_id')->toArray();

            if (!empty(array_intersect($postCategoryIds, $categoryIds))) {
                $home_posts[] = $post;
            }
        }
        return $home_posts;
    }

     //New(RIKO)//
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
