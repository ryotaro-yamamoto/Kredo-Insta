<?php

namespace App\Livewire;

use App\Models\Post;
use Livewire\Component;

class PostBody extends Component
{
    public $post;
    public int $comments_count;
    protected $listeners = ['commentCountUpdated' => 'updateCommentCount'];


    public function mount(Post $post)
    {
        $this->post = $post;
        $this->comments_count = $post->comments()->count();
    }

    public function updateCommentCount($postId, $newCount)
    {
        if ($this->post->id === $postId) {
            $this->comments_count = $newCount;
        }
    }

    public function render()
    {
        return view('livewire.post-body');
    }
}