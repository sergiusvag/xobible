<?php

namespace App\Http\Controllers\Offline;

use App\Models\Question;
use App\Models\Category;
use App\Models\QuestionCategory;
use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OfflineGameController extends BaseController 
{
    public function createQuestionsForRounds($locale, $questionCategoryId) {
        $questionsForRounds = [];
        $questions = Category::where('id', $questionCategoryId)->first()->questions;
        $upLocale = ucfirst($locale);

        $filteredQuestions = $questions->filter(function ($question, $key) use($upLocale) {
            return $question['question'.$upLocale]->confirmed === 1;
        });

        $maxRound = floor($filteredQuestions->count() / 9);
        $shuffledQuestions = $filteredQuestions->shuffle();

        for($i = 1; $i <= $maxRound; $i++) {
            $offset = (($i * 9) - 9);
            $upLocale = ucfirst($locale);
            for($j = 0; $j < 9; $j++) {
                $questionsForRounds[$i][$j] = $shuffledQuestions[$offset + $j]['question' . $upLocale];
            }
        }

        return $questionsForRounds;
    }

    public function index(Request $request, $locale) {
        $audioData = $this->getAudioData('offline-game');
        return view('game')
            ->with('data' , [
                'isOnline' => false,
                'host_color' => $request['host_color'],
                'join_color' => $request['join_color'],
                'question_category_id' => $request['question_category_id']
                ])
            ->with('rtlClass', $this->getRtlClass($locale))
            ->with('audioData', $audioData)
            ->with('locale', $locale);
    }

    public function load(Request $request, $locale) {
        $questions = $this->createQuestionsForRounds($locale, $request['question_category_id']);
        $maxRound = count($questions);

        return [
            'questions' => $questions,
            'maxRound' => $maxRound
        ];
    }
}