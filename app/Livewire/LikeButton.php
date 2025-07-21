<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class LikeButton extends Component
{
    public Post $post;

    public function toggleLike()
    {
        $user = Auth::user();
        if (!$user) return;

        if ($this->post->isLiked()) {
            $this->post->likes()->where('user_id', $user->id)->delete();
        } else {
            $this->post->likes()->create([
                'user_id' => $user->id,
            ]);
        }

        $this->post->refresh();
    }

    public function render()
    {
        return view('livewire.like-button');
    }
}
