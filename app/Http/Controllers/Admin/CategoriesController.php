<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;

class CategoriesController extends Controller{
    private $category;

    public function __construct(Category $category){
        $this->category = $category;
    }

    public function index(){
        $all_categories = $this->category->withCount('posts')->orderBy('updated_at', 'desc')->get();
        $uncategorized_count = Post::doesntHave('categoryPost')->count();
        return view('admin.categories.index', compact('all_categories', 'uncategorized_count'));
    }

    public function store(Request $request){
        //1. Validate all from data
        $request->validate([
            'name' => 'required|min:1|max:50'
        ]);

        //2. Save the category
        $this->category->name = $request->name;
        $this->category->save();

        //3. Redirect to the categories index page
        return redirect()->route('admin.categories');
    }

    public function show($id){
        $category = $this->category->findOrFail($id);
        return view('admin.categories.show')->with('category', $category);
    }

    public function update(Request $request, $id){
        //1. Validate all from data
        $request->validate([
            'name' => 'required|min:1|max:50'
        ]);

        //2. Update the category
        $category = $this->category->findOrFail($id);
        $category->name = $request->name;
        $category->save();

        //3. Redirect to the categories index page
        return redirect()->back();
    }

    public function destroy($id){
        $this->category->findOrFail($id)->delete();
        return redirect()->back();
    }
}
