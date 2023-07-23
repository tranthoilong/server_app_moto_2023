<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail;
use App\Mail\MailNotify;

class MailController extends Controller
{
    public function index() {
        $data = [
            'title' => 'Thanh toán đơn hàng thành công !',
            'body' => 'Đơn hàng của bạn đã được thanh toán thành công'
        ];

        Mail::to('senko0971@gmail.com')->send(new MailNotify($data));

    }
}
