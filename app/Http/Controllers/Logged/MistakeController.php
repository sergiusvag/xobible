<?php

namespace App\Http\Controllers\Logged;

use App\Models\Mistake;
use App\Models\User;
use App\Models\Question;
use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MistakeController extends BaseController
{
    public function index($locale)
    {
        return view('logged.mistake')
            ->with('rtlClass', $this->getRtlClass($locale))
            ->with('locale', $locale);
    }

    public function store(Request $request, $locale)
    {
        $id = Auth::id();
        $question_id = $request['question_id'];
        $question = Question::find($question_id);
        $mistakesSoFar = User::find($id)->mistakes->count();
        $backRoute = '/mistake/' . $locale;

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
            ->with('rtlClass', $this->getRtlClass($locale))
            ->with('success', __('Your mistake was successfully added!'))
            ->with('locale', $locale);
    }
}
