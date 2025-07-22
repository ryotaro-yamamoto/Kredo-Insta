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
        $this->loadComments();
    }

    public function render()
    {
        return view('livewire.comment-section');
    }

    public function toggleShowAll($value)
    {
        $this->showAll = $value;
        $this->loadComments();
    }

    public function addComment()
    {
        $this->validate();

        Comment::create([
            'post_id' => $this->post->id,
            'user_id' => Auth::id(),
            'body' => $this->newComment,
        ]);

        $this->newComment = '';
        $this->showAll = true;
        $this->loadComments();

        $newCount = $this->post->comments()->count();
        $this->dispatch('commentCountUpdated', $this->post->id, $newCount);
    }

    public function deleteComment($commentId)
    {
        $comment = Comment::findOrFail($commentId);
        if ($comment->user_id === Auth::id()) {
            $comment->delete();
            $this->loadComments();
        }
    }

    private function loadComments()
    {
        $query = $this->post->comments()->orderBy('created_at', 'asc');
        $this->comments = $query->get();
    }
}
