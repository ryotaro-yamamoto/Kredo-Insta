<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class CommentSection extends Component
{
    public $post;
    public $newComment = '';
    public $showAll = false;
    public $comments;

    protected $rules = [
        'newComment' => 'required|max:500',
    ];

    public function mount(Post $post)
    {
        $this->post = $post;
        $this->comments = $post->comments()->latest()->get();
    }

    public function render()
    {
        $this->comments = Comment::where('post_id', $this->post->id)
        ->orderBy('created_at', 'asc') 
        ->get();
        return view('livewire.comment-section');
    }

    public function toggleShowAll($value)
    {
        $this->showAll = $value;
    }

    public function addComment()
    {
        $this->validate();

        $comment = Comment::create([
            'body' => $this->newComment,
            'user_id' => Auth::id(),
            'post_id' => $this->post->id,
        ]);

        $this->newComment = '';
        $this->comments->prepend($comment);

        $this->dispatch('commentAdded');
    }

    public function deleteComment($commentId)
    {
        $comment = Comment::findOrFail($commentId);
        if ($comment->user_id === Auth::id()) {
            $comment->delete();
            $this->comments = $this->post->comments()->latest()->get();
        }
    }
}