<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    private $comment;

    public function __construct(Comment $comment){
        $this->comment = $comment;
    }
    public function store(Request $request, $post_id){
        // Validate the request
        $request->validate(
            [
                'comment_body'. $post_id => 'required|max:150'
            //comment_body4 is the name of the input field in the form, where 4 is the post_id
            ],
            [
                'comment_body' . $post_id . '.required' => 'You cannot submit an empty comment.', //required validation message
                'comment_body' . $post_id . '.max' => 'The comment must not have more than 150 characters.' //max validation message
            ]
        );

        // Create a new comment
        $this->comment->body = $request->input('comment_body' . $post_id);//$_POST['comment_body'] in the form
        $this->comment->user_id = Auth::user()->id; // Assuming user is authenticated
        $this->comment->post_id = $post_id;
        $this->comment->save();

        // Redirect back to the post with a success message
        return redirect()->route('post.show', $post_id);
    }

    public function destroy($id){
        $this->comment->destroy($id);
        return redirect()->back();
    }
}
