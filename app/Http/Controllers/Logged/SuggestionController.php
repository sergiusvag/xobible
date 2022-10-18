<?php

namespace App\Http\Controllers\Logged;

use App\Models\Suggestion;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuggestionController extends Controller
{
    public function create()
    {
        return view('logged.suggestion');
    }

    public function store(Request $request)
    {
        $id = Auth::id();
        $backRoute = '/welcome/' . app()->getLocale() . '/suggestion';

        Suggestion::create([
            'question' => $request['question'],
            'option_1' => $request['option_1'],
            'option_2' => $request['option_2'],
            'option_3' => $request['option_3'],
            'option_4' => $request['option_4'],
            'answer' => $request['answer'],
            'location' => $request['location'],
            'author_id' => $id,
        ]);

        return redirect($backRoute)
            ->with('success', __('Your suggestion was successfully added!'));
    }
}
