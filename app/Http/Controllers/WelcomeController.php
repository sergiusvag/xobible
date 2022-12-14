<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Room;
use App\Models\GameStatus;
use App\Models\QuestionStatus;
use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Logged\OnlineGameController;

class WelcomeController extends BaseController
{
    public function welcome($locale)
    {
        $page = view('welcome')
            ->with('locale', $locale);
        $data = [
            'btnClass' => '',
        ];
        
        if(Auth::user() !== null) {
            $room = Room::where('host_id', Auth::user()->id)
                            ->orWhere('join_id', Auth::user()->id)
                            ->first();
            $isInRoom =  $room !== null;
            $data = [
                'isInRoom' => $isInRoom,
                'btnUrl' => '/online-room/' . $locale,
                'btnText' => __('Online game'),
                'btnClass' => '',
                'hostOrJoinExitLink' => '',
            ];
            if($isInRoom) {
                if($room['status'] === 'in_color') {
                    $data['btnUrl'] = '/online-color-picker/' . $locale . '?room_number=' . $room->room_number;
                    $data['hostOrJoinExitLink'] = $room->host_name === Auth::user()->name ? '/close-room/'. $locale : '/exit-room/'. $locale;
                } else if ($room['status'] === 'in_game') {
                    $data['btnUrl'] = '/online-game/' . $locale . '?room_number=' . $room->room_number;
                    $data['hostOrJoinExitLink'] = '/welcome-exit-game/'. $locale;
                } 
                $data['btnText'] = __('Back to game');
                $data['btnClass'] = 'control-btn-dis';
                $data['room_number'] = $room->room_number;
            } 
        }
        return $page->with('data', $data);
    }

    public function exitGame(Request $request, $locale) {
        $gameStatus = GameStatus::where('room_number', $request['room_number'])->first();
        $room = Room::where('room_number', $request['room_number'])->delete();
        $questionStatuses = QuestionStatus::where('game_status_id', $gameStatus->id)->delete();
        $gameStatus->delete();

        return redirect('/welcome/'. $locale);
    }
}