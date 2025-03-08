<?php

namespace App\Mail;

use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PostStatusUpdatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $post;
    public $isApproved;

    public function __construct(Post $post, bool $isApproved)
    {
        $this->post = $post;
        $this->isApproved = $isApproved;
    }

 
    public function build()
    {
        $subject = $this->isApproved ? 
            "Bài viết '{$this->post->title}' của bạn đã được phê duyệt" : 
            "Bài viết '{$this->post->title}' của bạn đã bị từ chối";
        
        return $this->subject($subject)
                    ->view('email.post-status-updated');
    }
}