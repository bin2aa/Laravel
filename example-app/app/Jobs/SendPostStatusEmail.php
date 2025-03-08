<?php

namespace App\Jobs;

use App\Models\Post;
use App\Mail\PostStatusUpdatedMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendPostStatusEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $post;
    protected $isApproved;

    public function __construct(Post $post, bool $isApproved)
    {
        $this->post = $post;
        $this->isApproved = $isApproved;
    }


    public function handle(): void
    {
        $user = $this->post->user;
        if ($user && $user->email) {
            Mail::to($user->email) // sử dụng phương thức to() để xác định địa chỉ email người nhận
                ->send(new PostStatusUpdatedMail($this->post, $this->isApproved));
        }
    }
}