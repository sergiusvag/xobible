<?php

namespace App\Http\Controllers\Logged;

use App\Models\Room;
use App\Models\Question;
use App\Models\GameStatus;
use App\Models\QuestionStatus;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\GameTileSelected;
use App\Events\GameOptionSelected;
use App\Events\GameQuestionAnswered;
use App\Events\GameCloseResult;

class OnlineGameController extends Controller
{
    public function createQuestionStatuses($room_number, $gameStatusId) {
        $questions = Question::All();
        $maxRound = floor($questions->count() / 9);
        $questionsArray = $questions->toArray();;
        shuffle($questionsArray);
        
        for($i = 1; $i <= $maxRound; $i++) {
            $offset = (($i * 9) - 9);
            $questionStatus = new QuestionStatus([
                'room_number' => $room_number,
                'round_number' => $i,
                '0_field_question_id' => $questionsArray[$offset + 0]['id'],
                '1_field_question_id' => $questionsArray[$offset + 1]['id'],
                '2_field_question_id' => $questionsArray[$offset + 2]['id'],
                '3_field_question_id' => $questionsArray[$offset + 3]['id'],
                '4_field_question_id' => $questionsArray[$offset + 4]['id'],
                '5_field_question_id' => $questionsArray[$offset + 5]['id'],
                '6_field_question_id' => $questionsArray[$offset + 6]['id'],
                '7_field_question_id' => $questionsArray[$offset + 7]['id'],
                '8_field_question_id' => $questionsArray[$offset + 8]['id'],
                'game_status_id' => $gameStatusId,
            ]);
            $questionStatus->save();
        }
    }

    public function index(Request $request, $locale)
    {
        $room_number = $request->room_number;
        $gameStatus = GameStatus::where('room_number', $room_number)->first();
        $currentRound = $gameStatus->current_round;

        $questionStatuses = QuestionStatus::where('game_status_id', $gameStatus->id)->get();
        if($questionStatuses->isEmpty()){
            $this->createQuestionStatuses($room_number, $gameStatus->id);
        }

        return view('logged.online-game')
            ->with('data' , ['room_number' => $room_number])
            ->with('locale', $locale);
    }

    public function load(Request $request, $locale) {
        $room_number = $request->room_number;
        $gameStatus = GameStatus::where('room_number', $room_number)->first();
        $questionStatus = QuestionStatus::where('game_status_id', $gameStatus->id)
                                            ->where('round_number', $gameStatus->current_round)
                                            ->first();
        $data = ['game_status' => $gameStatus];                       
        if($questionStatus !== null) {
            $questions = [];
            for($i = 0; $i < 9; $i++) {
                $questions[$i] = $questionStatus['question_'.$i];
            }
            if($locale !== 'en'){
                $upLocale = ucfirst($locale);
                for($i = 0; $i < 9; $i++) {
                    $questions[$i] = $questions[$i]['question' . $upLocale];
                }
            }
            $data['questions'] = $questions;
            $data['question_status'] = $questionStatus;
        } 
        
        $data['i_am'] = $gameStatus->host_name === Auth::user()->name ? 'host' : 'join';
        $data['i_am_upper'] = ucfirst($data['i_am']);

        return $data;
    }

    public function tileSelected(Request $request, $locale) {
        $gameStatus = GameStatus::where('room_number', $request['room_number'])->first();
        $questionStatus = QuestionStatus::where('game_status_id', $gameStatus->id)
                                            ->where('round_number', $gameStatus->current_round)
                                            ->first();
        
        GameTileSelected::dispatch($request['room_number'], ['index' => $request['index']]);

        $gameStatus['status'] = "in_question";
        $questionStatus['selected_field'] = $request['index'] . '_field';
        $gameStatus->save();
        $questionStatus->save();

        return [];
    }

    public function optionSelected(Request $request, $locale) {
        broadcast(new GameOptionSelected($request['room_number'], ['index' => $request['index']]))->toOthers();

        return [];
    }

    public function questionAnswered(Request $request, $locale) {
        $data = [
            'index' => $request['index'],
            'is_correct' => $request['is_correct'],
        ];
        $gameStatus = GameStatus::where('room_number', $request['room_number'])->first();

        broadcast(new GameQuestionAnswered($request['room_number'],  $data))->toOthers();

        $gameStatus['result'] = $request['is_correct'] ? "is_correct" : "is_wrong";
        $gameStatus['status'] = "in_result ";
        $gameStatus->save();
        
        return [];
    }

    public function closeResult(Request $request, $locale) {
        $data = [
            'index' => $request['index'],
            'is_correct' => $request['is_correct'],
            'bonus' => $request['bonus'],
            'is_all_full' => $request['is_all_full'],
        ];
        $gameStatus = GameStatus::where('room_number', $request['room_number'])->first();
        $questionStatus = QuestionStatus::where('game_status_id', $gameStatus->id)
                                            ->where('round_number', $gameStatus->current_round)
                                            ->first();

        $currentPlayer = $gameStatus['current_player'];
        $nextPlayer = $currentPlayer === "host" ? "join" : "host";
        $selected_field = $questionStatus['selected_field'];
        $gameStatus['current_player'] = $nextPlayer;
        $gameStatus['status'] = "in_board ";

        if($request['is_correct']) {
            $questionStatus[$selected_field."_question_status"] = $currentPlayer."_answered";
            $gameStatus[$currentPlayer."_current_score"] += 1;
            $gameStatus[$currentPlayer."_score"] += 1;
            $gameStatus[$currentPlayer."_current_total_score"] += 1 + $request['bonus'];
            $gameStatus[$currentPlayer."_total_score"] += 1 + $request['bonus'];
            $gameStatus[$currentPlayer."_current_bonus_score"] += $request['bonus'];
            $gameStatus[$currentPlayer."_bonus_score"] += $request['bonus'];
        } else {
            $gameStatus[$currentPlayer."_current_wrong_score"] += 1;
            $gameStatus[$currentPlayer."_wrong_score"] += 1;
        }
        $questionStatus['selected_field'] = "none";
        $gameStatus['result'] = "none";

        $gameStatus->save();
        $questionStatus->save();
        broadcast(new GameCloseResult($request['room_number'], $data))->toOthers();

        return [];
    }
}