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
    public function create()
    {
        $name = Auth::user()->name;
        // event(new MessageNotification('This is our Message'));
        
        event(new MessageNotification($name));

        return view('logged.online-room');
    }

    public function listen(Request $request)
    {
        event(new MessageNotification($request['chat-id']));

        return view('logged.online-room');
    }
}
