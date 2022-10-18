<?php

namespace App\Http\Controllers\Logged;

use App\Models\Mistake;
use App\Models\User;
use App\Models\Question;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MistakeController extends Controller
{
    public function create()
    {
        return view('logged.mistake');
    }

    public function store(Request $request)
    {
        $id = Auth::id();
        $question_id = $request['question_id'];
        $question = Question::find($question_id);
        $mistakesSoFar = User::find($id)->mistakes->count();
        $backRoute = '/welcome/' . app()->getLocale() . '/mistake';

        if($question === null) {
            return redirect($backRoute)
                ->with('question', __('Such question № does not exist'))
                ->with('question_id', $question_id)
                ->with('mistake', $request['mistake']);
        }

        Mistake::create([
            'question_id' => $question_id,
            'mistake' => $request['mistake'],
            'author_id' => $id,
        ]);

        return redirect($backRoute)
            ->with('success', __('Your mistake was successfully added!'));
    }
}
