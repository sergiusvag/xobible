<?php

namespace App\Http\Controllers\Offline;

use App\Models\Question;
use App\Http\Controllers\GameController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OfflineGameController extends GameController 
{
    public function createQuestionsForRounds($locale) {
        $questionsForRounds = [];
        $questions = Question::All();
        $maxRound = floor($questions->count() / 9);
        $shuffledQuestions = $questions->shuffle();

        for($i = 1; $i <= $maxRound; $i++) {
            $offset = (($i * 9) - 9);
            $temp = [];
            $quest = $shuffledQuestions[0];
            if($locale !== 'en'){
                $upLocale = ucfirst($locale);
                for($j = 0; $j < 9; $j++) {
                    $temp[$j] = $shuffledQuestions[$offset + $j]['question' . $upLocale];
                }
            } else {
                for($j = 0; $j < 9; $j++) {
                    $temp[$j] = $shuffledQuestions[$offset + $j];
                }
            }

            $questionsForRounds[$i] = $temp;
        }

        return $questionsForRounds;
    }

    public function index(Request $request, $locale) {
        return view('logged.online-game')
            ->with('data' , ['isOnline' => false, 'host_color' => $request['host_color'], 'join_color' => $request['join_color']])
            ->with('rtlClass', $this->getRtlClass($locale))
            ->with('locale', $locale);
    }

    public function load(Request $request, $locale) {
        $questions = $this->createQuestionsForRounds($locale);
        $maxRound = count($questions);

        return [
            'questions' => $questions,
            'maxRound' => $maxRound
        ];
    }
}