<?php

namespace App\Http\Controllers;

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
        $home_posts = $this->getHomePosts();
        $suggested_users = $this->getSuggestedUser();
        return view('users.home')
                ->with('home_posts', $home_posts)
                ->with('suggested_users', $suggested_users);
    }

    public function getHomePosts(){
        $all_posts = $this->post->latest()->get();
        $home_posts = [];

        foreach ($all_posts as $post) {
            if($post->user->isFollowed() || $post->user->id === Auth::user()->id){
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
    public function search(Request $request){
        $users = $this->user->where('name', 'like', '%' . $request->search . '%')->where('id', '!=', auth()->id())->get();
        return view('users.search')->with('users', $users)->with('search', $request->search);
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
}
