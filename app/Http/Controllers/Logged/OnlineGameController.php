<?php

namespace App\Http\Controllers\Logged;

use App\Models\Room;
use App\Models\Question;
use App\Models\Category;
use App\Models\GameStatus;
use App\Models\QuestionStatus;
use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\GameTileSelected;
use App\Events\GameOptionSelected;
use App\Events\GameQuestionAnswered;
use App\Events\GameCloseResult;
use App\Events\GameOver;
use App\Events\GameNextRoundClicked;

class OnlineGameController extends BaseController
{
    public function createQuestionStatuses($room_number, $gameStatusId, $questionCategoryId) {
        $questions = Category::where('id', $questionCategoryId)->first()->questions;
        $maxRound = floor($questions->count() / 9);
        $shuffledQuestions = $questions->shuffle();
        
        for($i = 1; $i <= $maxRound; $i++) {
            $offset = (($i * 9) - 9);
            $questionStatus = new QuestionStatus([
                'room_number' => $room_number,
                'round_number' => $i,
                '0_field_question_id' => $shuffledQuestions[$offset + 0]['id'],
                '1_field_question_id' => $shuffledQuestions[$offset + 1]['id'],
                '2_field_question_id' => $shuffledQuestions[$offset + 2]['id'],
                '3_field_question_id' => $shuffledQuestions[$offset + 3]['id'],
                '4_field_question_id' => $shuffledQuestions[$offset + 4]['id'],
                '5_field_question_id' => $shuffledQuestions[$offset + 5]['id'],
                '6_field_question_id' => $shuffledQuestions[$offset + 6]['id'],
                '7_field_question_id' => $shuffledQuestions[$offset + 7]['id'],
                '8_field_question_id' => $shuffledQuestions[$offset + 8]['id'],
                'game_status_id' => $gameStatusId,
            ]);
            $questionStatus->save();
        }

        return $maxRound;
    }

    public function index(Request $request, $locale)
    {
        $user = Auth::user();
        $room_number = $request->room_number;
        $gameStatus = GameStatus::where('room_number', $room_number)->first();

        if($gameStatus === null){ 
            $room = Room::where('room_number', $request['room_number'])->delete();
            return redirect('/welcome/'. $locale);
        }
        $questionStatuses = QuestionStatus::where('game_status_id', $gameStatus->id)->get();

        if($questionStatuses->isEmpty()){
            $maxRound = $this->createQuestionStatuses($room_number, $gameStatus->id, $gameStatus->question_category_id);
        } else {
            $maxRound = $questionStatuses->count();
        }

        $audioData = $this->getAudioData('online-game');

        return view('game')
            ->with('data' , [
                'isOnline' => true,
                'room_number' => $room_number,
                'max_round' => $maxRound,
                'current_round' => $gameStatus->current_round,
                'question_category_id' => $gameStatus->question_category_id
                ])
            ->with('rtlClass', $this->getRtlClass($locale))
            ->with('audioData', $audioData)
            ->with('locale', $locale);
    }

    public function extractQuestions($questionStatus, $locale) {
        $questions = [];
        $upLocale = ucfirst($locale);

        for($i = 0; $i < 9; $i++) {
            $questions[$i] = $questionStatus['question_'.$i]['question' . $upLocale];
        }

        return $questions;
    }

    public function load(Request $request, $locale) {
        $room_number = $request->room_number;
        $gameStatus = GameStatus::where('room_number', $room_number)->first();
        $questionStatus = QuestionStatus::where('game_status_id', $gameStatus->id)
                                            ->where('round_number', $gameStatus->current_round)
                                            ->first();
        $data = ['game_status' => $gameStatus];                       
        if($questionStatus !== null) {
            $data['questions'] =  $this->extractQuestions($questionStatus, $locale);
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
        $gameStatus = GameStatus::where('room_number', $request['room_number'])->first();
        $gameStatus['selected_option'] = $request['index'];
        $gameStatus->save();
        
        broadcast(new GameOptionSelected($request['room_number'], ['index' => $request['index']]))->toOthers();

        return [];
    }

    public function questionAnswered(Request $request, $locale) {
        $data = [
            'index' => $request['index'],
            'is_correct' => $request['is_correct'],
        ];
        $gameStatus = GameStatus::where('room_number', $request['room_number'])->first();

        broadcast(new GameQuestionAnswered($request['room_number'], $data))->toOthers();

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
        $status = $request['is_all_full'] ? "in_round" : "in_board ";
        $gameStatus['status'] = $status;

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

    public function gameOver(Request $request, $locale) {
        $gameStatus = GameStatus::where('room_number', $request['room_number'])->first();
        $gameStatus['status'] = 'in_over';
        $gameStatus->save();

        broadcast(new GameOver($request['room_number'], []))->toOthers();
        
        return [];
    }

    public function nextRoundStatus($gameStatus) {
        $gameStatus['host_current_score'] = 0;
        $gameStatus['host_current_wrong_score'] = 0;
        $gameStatus['host_current_bonus_score'] = 0;
        $gameStatus['host_current_total_score'] = 0;
        $gameStatus['join_current_score'] = 0;
        $gameStatus['join_current_wrong_score'] = 0;
        $gameStatus['join_current_bonus_score'] = 0;
        $gameStatus['join_current_total_score'] = 0;
        $gameStatus['current_round'] += 1;
        $gameStatus['status'] = 'in_board';
        $gameStatus['result'] = 'none';

        return $gameStatus;
    }
    public function nextRound(Request $request, $locale) {
        $gameStatus = GameStatus::where('room_number', $request['room_number'])->first();
        $gameStatus = $this->nextRoundStatus($gameStatus);
        
        $questionStatus = QuestionStatus::where('game_status_id', $gameStatus->id)
                                            ->where('round_number', $gameStatus->current_round)
                                            ->first();

        $data = ['game_status' => $gameStatus];                       
        if($questionStatus !== null) {
            $data['questions'] =  $this->extractQuestions($questionStatus, $locale);
            $data['question_status'] = $questionStatus;
        } 

        $gameStatus->save();
        broadcast(new GameNextRoundClicked($request['room_number'], []))->toOthers();

        return $data;
    }

    public function newRoundJoin(Request $request, $locale) {
        $gameStatus = GameStatus::where('room_number', $request['room_number'])->first();
        $questionStatus = QuestionStatus::where('game_status_id', $gameStatus->id)
                                            ->where('round_number', $gameStatus->current_round)
                                            ->first();

        $data = ['game_status' => $gameStatus];                       
        if($questionStatus !== null) {
            $data['questions'] =  $this->extractQuestions($questionStatus, $locale);
            $data['question_status'] = $questionStatus;
        } 
        
        return $data;
    }

    public function newGame(Request $request, $locale) {
        $gameStatus = GameStatus::where('room_number', $request['room_number'])->first();
        $questionStatuses = QuestionStatus::where('game_status_id', $gameStatus->id)->delete();

        $gameStatus['host_current_score'] = 0;
        $gameStatus['host_current_wrong_score'] = 0;
        $gameStatus['host_current_bonus_score'] = 0;
        $gameStatus['host_current_total_score'] = 0;
        $gameStatus['join_current_score'] = 0;
        $gameStatus['join_current_wrong_score'] = 0;
        $gameStatus['join_current_bonus_score'] = 0;
        $gameStatus['join_current_total_score'] = 0;
        $gameStatus['host_score'] = 0;
        $gameStatus['host_wrong_score'] = 0;
        $gameStatus['host_bonus_score'] = 0;
        $gameStatus['host_total_score'] = 0;
        $gameStatus['join_score'] = 0;
        $gameStatus['join_wrong_score'] = 0;
        $gameStatus['join_bonus_score'] = 0;
        $gameStatus['join_total_score'] = 0;
        $gameStatus['current_round'] = 1;
        $gameStatus['status'] = 'in_board';
        $gameStatus['result'] = 'none';

        $gameStatus->save();

        return [];
    }

    public function finishGame(Request $request, $locale) {
        $gameStatus = GameStatus::where('room_number', $request['room_number'])->first();
        $room = Room::where('room_number', $request['room_number'])->delete();
        $questionStatuses = QuestionStatus::where('game_status_id', $gameStatus->id)->delete();
        $gameStatus->delete();

        return [];
    }
}

