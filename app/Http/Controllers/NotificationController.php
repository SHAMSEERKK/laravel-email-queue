<?php

namespace App\Http\Controllers;

use App\Jobs\SendEmailJob;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function  sendNotification()
    {
        $emailDeatails = [
            'to' => 'shamseer@gmail.com',
            'subject' => 'User Registration',
            'body' => 'congratulations , your registartion completed succesfully',
        ];
        SendEmailJob::dispatch($emailDeatails);
        return response()->json(['message' => 'email send succesfully']);
    }
}
