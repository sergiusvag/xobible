<?php

namespace App\Http\Controllers\Logged;

use App\Models\Mistake;
use App\Models\User;
use App\Models\Question;
use App\Events\MessageNotification;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OnlineGameController extends Controller
{
    public function listen()
    {
        return view('logged.online-room');
    }

    public function createEvent()
    {
        event(new MessageNotification('This is our Message'));
    }
}
