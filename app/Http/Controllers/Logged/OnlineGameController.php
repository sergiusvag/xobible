<?php

namespace App\Http\Controllers\Logged;

use App\Models\Room;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OnlineGameController extends Controller
{
    public function index(Request $request, $locale)
    {
        $room = 'hello';
        return view('logged.online-game')
            ->with('locale', $locale);
    }
}