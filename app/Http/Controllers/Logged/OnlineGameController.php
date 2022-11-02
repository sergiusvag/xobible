<?php

namespace App\Http\Controllers\Logged;

use App\Models\Mistake;
use App\Models\User;
use App\Models\Question;
use App\Models\Room;
use App\Events\JoinNotification;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OnlineGameController extends Controller
{
    public function online()
    {
        return view('logged.online-room');
    }

    public function listen(Request $request)
    {
        $i = 1;

        return view('logged.online-room');
    }

    public function loadDataFromRoom($room) {
        return [
            'host_name' => $room->host_name,
            'join_name' => $room->join_name,
            'room_number' => $room->room_number,
            'room_key'=> $room->room_key,
            'in_room' => true
        ];
    }

    public function checkOnLoad() {
        $roomHost = Room::where('host_id', Auth::user()->id)->first();
        
        $data = ['in_room' => false];

        if($roomHost !== null) {
            $data = $this->loadDataFromRoom($roomHost);
            $data['is_host'] = true;
            if($data['join_name'] !== null) {
                $data['message'] = __('Joined Room');
            } else {
                $data['message'] = __('Rejoined Room');
            }
        } else {
            $roomJoin = Room::where('join_id', Auth::user()->id)->first();

            if($roomJoin !== null) {
                $data = $this->loadDataFromRoom($roomJoin);
                $data['is_host'] = false;
                $data['message'] = __('You successfully rejoined room');
            } 
        }

        return $data;
    }

    public function create(Request $request) {
        $roomKey = $request['roomKey'];
        $rooms = Room::all()->toArray();

        do {
            $roomNum = rand(100000,999999);
        }
        while(in_array($roomNum, $rooms));

        $room = new Room([
            'room_number' => $roomNum,
            'host_name' => Auth::user()->name,
            'host_id' => Auth::user()->id,
            'room_key' => $roomKey,
        ]);

        $room->save();

        return ['room_number' => $roomNum, 'host_name' => Auth::user()->name, 'message' => __('Room created')];
    }
    
    public function close(Request $request) {
        $roomNum = $request['roomNum'];
        Room::where('room_number', $roomNum)->delete();

        return ['message' => __('Room closed by host')];
    }

    public function join(Request $request) {
        $roomNum = $request['roomNum'];
        $roomKey = $request['roomKey'];
        $msg = __('Failed to join room');
        $data = [
            'room_number' => $roomNum,
            'joinSuccess' => false,
            'message' => $msg,
            'message_for_host' => null,
            'host_name' => null,
            'join_name' => Auth::user()->name,
        ];
        $room = Room::where('room_number', $roomNum)->first();

        if($room !== null) {
            if($room->join_name === null && $room->room_key == $roomKey) {
                $room->forceFill([
                    'room_number' => $roomNum,
                    'host_name' => $room->host_name,
                    'host_id' => $room->host_id,
                    'join_name' => Auth::user()->name,
                    'join_id' => Auth::user()->id,
                    'room_key' => $roomKey,
                ])->save();
                $data['joinSuccess'] = true;
                $data['message'] = __('You successfully joined room');
                $data['message_for_host'] = __('Joined Room');
                $data['host_name'] = $room->host_name;
            }
        }

        return $data;
    }

    public function kick(Request $request) {
        $room = Room::where('room_number', $request['roomNum'])->first();

        $data = [
            'join_name' => $room->join_name,
            'message' => __('Successfully kicked'),
            'message_for_join' => __('You were kicked'),
        ];

        $fields = [
            'room_number' => $room->room_number,
            'host_name' => $room->host_name,
            'host_id' => $room->host_id,
            'join_name' => null,
            'join_id' => null,
            'room_key' => $room->room_key,
        ];

        $room->forceFill($fields)->save();

        return $data;
    }

    public function exit(Request $request) {
        $data = $this->kick($request);
        $data['message_for_host'] = __('Player left the room');
        $data['message'] = __('You successfully left the room');

        return $data;
    }
}
