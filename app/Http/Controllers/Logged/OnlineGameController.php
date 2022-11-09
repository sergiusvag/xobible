<?php

namespace App\Http\Controllers\Logged;

use App\Models\Mistake;
use App\Models\User;
use App\Models\Question;
use App\Models\Room;
use App\Events\HostRoomEventJoin;
use App\Events\MemberRoomEventJoin;
use App\Events\RoomEventClose;
use App\Events\RoomEventStart;
use App\Events\HostRoomEventKicked;
use App\Events\MemberRoomEventKicked;
use App\Events\HostRoomEventExit;
use App\Events\MemberRoomEventExit;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OnlineGameController extends Controller
{
    public function online()
    {
        return view('logged.online-room');
    }

    public function loadDataFromRoom($room, $isHost) {
        return [
            'host_name' => $room->host_name,
            'join_name' => $room->join_name,
            'room_number' => $room->room_number,
            'room_key'=> $room->room_key,
            'status' => $room['status'],
            'is_host' => $isHost,
            'channel' => 'room.'.$room->room_number,
            'message' => __('You successfully rejoined room'),
        ];
    }

    public function checkOnLoad() {
        $room = Room::where('host_id', Auth::user()->id)->first();

        $data = ['status' => false];

        if($room !== null) {
            $data = $this->loadDataFromRoom($room, true);
        } else {
            $room = Room::where('join_id', Auth::user()->id)->first();

            if($room !== null) {
                $data = $this->loadDataFromRoom($room, false);
            } 
        }

        return $data;
    }

    public function findEmptyRoom($rooms) {
        $min = 100000;
        $max = 999999;
        $tries = 0;
        $maxTries = 10;
        do {
            $tries++;
            $roomNum = rand($min,$max);
            if($tries >= $maxTries) {
                $min *= 10;
                $max *= 10;
                $tries = 0;
            }
        }
        while(in_array($roomNum, $rooms));

        return $roomNum;
    }

    public function create(Request $request) {
        $roomKey = $request['roomKey'];
        $roomNum = $this->findEmptyRoom(Room::all()->toArray());

        $room = new Room([
            'room_number' => $roomNum,
            'host_name' => Auth::user()->name,
            'host_id' => Auth::user()->id,
            'room_key' => $roomKey,
        ]);

        $room->save();

        return [
            'room_number' => $roomNum,
            'room_key' => $roomKey,
            'host_name' => Auth::user()->name,
            'message' => __('Room created'), 
            'channel' => 'room.'.$roomNum,
        ];
    }
    
    public function close(Request $request) {
        $roomNum = $request['roomNum'];
        Room::where('room_number', $roomNum)->delete();

        RoomEventClose::dispatch($roomNum, __('Room closed by host'));
        return [];
    }

    public function join(Request $request) {
        $roomNum = $request['roomNum'];
        $roomKey = $request['roomKey'];
        $data = [
            'joinSuccess' => false,
            'message' => __('Error while joining room'),
            'channel' => 'room.'.$roomNum,
        ];
        $room = Room::where('room_number', $roomNum)
                    ->where('join_name', null)
                    ->where('room_key', $roomKey)
                    ->first();

        if($room !== null) {
                $room->join_name = Auth::user()->name;
                $room->join_id = Auth::user()->id;
                $room->save();
                $data['joinSuccess'] = true;
                HostRoomEventJoin::dispatch($room, Auth::user()->name.' '.__('Joined Room'));
                MemberRoomEventJoin::dispatch($room, __('You successfully joined room'));
        }

        return $data;
    }

    public function removeFromDB(Request $request) {
        $room = Room::where('room_number', $request['roomNum'])->first();
        $fields = [
            'room_number' => $room->room_number,
            'host_name' => $room->host_name,
            'host_id' => $room->host_id,
            'join_name' => null,
            'join_id' => null,
            'room_key' => $room->room_key,
        ];
        $room->forceFill($fields)->save();
    }

    public function kick(Request $request) {
        $this->removeFromDB($request);
        HostRoomEventKicked::dispatch($request['roomNum'], __('Successfully kicked'). ' ' . $request['joinName']);
        MemberRoomEventKicked::dispatch($request['roomNum'], __('You were kicked'));

        return [];
    }

    public function exit(Request $request) {
        $this->removeFromDB($request);
        HostRoomEventExit::dispatch($request['roomNum'], __('Player left the room'). ' ' . $request['joinName']);
        MemberRoomEventExit::dispatch($request['roomNum'], __('You successfully left the room'));

        return [];
    }

    public function start(Request $request) {
        $room = Room::where('room_number', $request['roomNum'])->first();
        $room->status = 'in_game';
        $room->save();

        RoomEventStart::dispatch($request['roomNum']);

        return [];
    }
}
