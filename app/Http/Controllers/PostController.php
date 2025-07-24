<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;
use App\Models\Category;

class PostController extends Controller{
    private $post;
    private $category;

    public function __construct(Post $post, Category $category){
        $this->post = $post;
        $this->category = $category;
    }

    public function index() {
        // SoftDeletes利用時、論理削除(deleted_at入り)は自動で除外される
        $posts = $this->post->where('user_id', Auth::id())->latest()->get();
        return view('users.posts.index')->with('posts', $posts);
    }

    public function create(){
        $all_categories = $this->category->all();
        return view('users.posts.create')->with('all_categories', $all_categories);
    }

    public function store(Request $request){
        //1. Validate all from data
        $request->validate([
            'category' => 'required|array|between:1,3',
            'description' => 'required|min:1|max:1000',
            'images' => 'required',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        //2. Save the post
        $this->post->user_id = Auth::user()->id;
        $this->post->description = $request->description;
        $this->post->save();

        //3. Save the categories to the category_post table
        foreach ($request->category as $category_id) {
            $category_post[] = ['category_id' => $category_id];
        }
        $this->post->categoryPost()->createMany($category_post);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('post_images', 'public');
                $this->post->images()->create(['image_path' => $path]);
            }
        }

        //4. Redirect to the home page
        return redirect()->route('index');
    }

    public function show($id){
        $post = $this->post->findOrFail($id);
        return view('users.posts.show')->with('post', $post);
    }

    public function edit($id){
        $post = $this->post->findOrFail($id);

        // Check if the authenticated user is the owner of the post, redirect to homepage
        if (Auth::user()->id != $post->user_id) {
            return redirect()->route('index');
        }

        $all_categories = $this->category->all();

        // get all the category_ids of the post. Save in an array
        $selected_categories = [];
        foreach ($post->categoryPost as $category_post) {
            $selected_categories[] = $category_post->category_id;
        }

        return view('users.posts.edit')
                ->with(['post' => $post, 'all_categories' => $all_categories, 'selected_categories' => $selected_categories]);
    }

    public function update(Request $request, $id){
        //1. Validate all from data
        $request->validate([
            'category' => 'required|array|between:1,3',
            'description' => 'required|min:1|max:1000',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        //2. Update the post
        $post = $this->post->findOrFail($id);
        $post->description = $request->description;
        if ($request->image) {
            $post->image = 'data:image/' . $request->image->extension() . ';base64,' . base64_encode(file_get_contents($request->image));
        }
        $post->save();

        //3. Update the categories to the category_post table
        $post->categoryPost()->delete();
        // Use the relationship Post::categoryPost() to select the records related to a post
        foreach ($request->category as $category_id) {
            $category_post[] = ['category_id' => $category_id];
        }
        $post->categoryPost()->createMany($category_post);
        
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('post_images', 'public');
                $post->images()->create(['image_path' => $path]);
            }
        }


        //4. Redirect to the home page
        return redirect()->route('post.show', $id);
    }

    public function destroy($id){
        $post = $this->post->findOrFail($id);
        $post->forceDelete(); // ← 完全削除（物理削除）
        // Redirect to the home page
        return redirect()->route('index');
    }
}
