<?php

namespace App\Livewire;

use Livewire\Component;

class PostBody extends Component
{
    public $post;
    public $commentCount;

    protected $listeners = ['commentAdded' => 'updateCommentCount'];

    public function mount($post)
    {
        $this->commentCount = $post->comments->count();
    }

    public function updateCommentCount()
    {
        $this->commentCount = $this->post->comments()->count();
    }

    public function render()
    {
        return view('livewire.post-body');
    }
}