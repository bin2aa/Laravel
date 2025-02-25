<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;


class RegisterMail extends Mailable
{
    use Queueable, SerializesModels;

    public function build()
    {
        return $this->view('email.register')
            // ->from('asjdasdasdsd@gmail.com', 'Nguyen Thanh Thinh') // địa chỉ email và tên người gửi
            ->subject('Đăng ký tài khoản'); // tiêu đề email
    }
}
