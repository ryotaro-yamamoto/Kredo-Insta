<?php

namespace App\Http\Controllers;

use App\Models\Advertise;
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
    public function index(){
        // $home_posts = $this->getHomePosts();
        // $suggested_users = $this->getSuggestedUser();
        // return view('users.home')
        //         ->with('home_posts', $home_posts)
        //         ->with('suggested_users', $suggested_users);
        [$home_posts, $ads] = $this->getHomePosts(); // ← 広告も取得
        $suggested_users = $this->getSuggestedUser();

        return view('users.home')
            ->with('home_posts', $home_posts)
            ->with('ads', $ads)
            ->with('suggested_users', $suggested_users);
    }

    //New(RIKO)
    // public function getHomePosts(){
        // $user = Auth::user();
        // $all_posts = $this->post->latest()->get();
        // $home_posts = [];

        // $ads = Advertise::whereHas('interests', function ($query) use ($user) {
        //     $query->whereIn('interests.id', $user->interests->pluck('id'));
        // })->get();
        // return view('users.home', [
        //     'home_posts' => $all_posts,
        //     'ads' => $ads,
        // ]);

        // foreach ($all_posts as $post) {
        //     if($post->user->isFollowed() || $post->user->id === Auth::user()->id){
        //         $home_posts[] = $post;
        //     }
        // }
        // return $home_posts;

        //         $user = Auth::user();
        //         $all_posts = $this->post->latest()->get();
        //         $home_posts = [];
            
        //         $ads = Advertise::whereHas('interests', function ($query) use ($user) {
        //             $query->whereIn('interests.id', $user->interests->pluck('id'));
        //         })->get();
            
        //         return view('users.home', [  // ❌これが index() を止めてる
        //             'home_posts' => $all_posts,
        //             'ads' => $ads,
        //         ]);
                
        //         // ここ以降は実行されない
        //         foreach ($all_posts as $post) {
        //             if($post->user->isFollowed() || $post->user->id === Auth::user()->id){
        //                 $home_posts[] = $post;
        //             }
        //         }
        //         return $home_posts;
        
    // }

    public function getHomePosts(){
        $user = Auth::user();
        $all_posts = $this->post->latest()->get();
        $home_posts = [];
    
        // 条件に合う広告
        $ads = Advertise::whereHas('interests', function ($query) use ($user) {
            $query->whereIn('interests.id', $user->interests->pluck('id'));
        })->get();
    
        // フォロー中または自分の投稿だけ抽出
        foreach ($all_posts as $post) {
            if($post->user->isFollowed() || $post->user->id === $user->id){
                $home_posts[] = $post;
            }
        }
    
        return [$home_posts, $ads]; // ✅ データだけ返す
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
