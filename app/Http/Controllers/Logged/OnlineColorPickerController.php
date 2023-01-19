<?php

namespace App\Http\Controllers\Logged;

use App\Models\Room;
use App\Models\GameStatus;
use App\Events\ColorEventStart;
use App\Http\Controllers\ColorPickerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OnlineColorPickerController extends ColorPickerController
{
    public function index(Request $request, $locale)
    {
        $room_number = $request->room_number;
        $room = Room::where('room_number', $room_number)->first();
        
        if($room === null) {
            return redirect('/welcome/'. $locale);
        }
        if($room->host_name !== Auth::user()->name && $room->join_name !== Auth::user()->name) {
            abort(403);
        }
        if($room->status === 'in_room') {
            return redirect('/online-room/'. $locale);
        }
        if($room->status === 'in_game') {
            return redirect('/online-game/'. $locale .'?room_number='.$room_number);
        }

        $isHost = $room->host_name === Auth::user()->name;
        $playersBtnClass = $isHost ? ['', 'control-dis'] : ['control-dis', ''];
        
        $data = $this->getDefaultData(true);
        $data['room_number'] = $room_number;
        $data['isHost'] = $isHost ? 'is_host' : 'is_member';
        $data['playersBtnClass'] = $playersBtnClass;
        $data['playerTitleText'] = [$room->host_name, $room->join_name];

        $audioData = $this->getAudioData('color-picker');
        
        return view('logged.online-color-picker')
            ->with('data', $data)
            ->with('audioData', $audioData)
            ->with('locale', $locale);
    }

    public function start(Request $request) {
        $room = Room::where('room_number', $request['room_number'])->first();
        $room->status = 'in_game';
        $room->save();

        $gameStatus = new GameStatus([
            'host_id' => $room->host_id,
            'host_name' => $room->host_name,
            'host_color' => $request['host_color'],
            'join_id' => $room->join_id,
            'join_name' => $room->join_name,
            'join_color' => $request['join_color'],
            'room_number' => $room->room_number,
            'question_category_id' => $request['question_category_id'],
            'status' => 'in_board'
        ]);
        $gameStatus->save();

        ColorEventStart::dispatch($room);

        return [];
    }
}