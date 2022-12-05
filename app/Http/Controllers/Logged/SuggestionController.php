<?php

namespace App\Http\Controllers\Logged;

use App\Models\Suggestion;
use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuggestionController extends BaseController
{
    public function index($locale)
    {
        return view('logged.suggestion')
            ->with('rtlClass', $this->getRtlClass($locale))
            ->with('locale', $locale);
    }

    public function store(Request $request, $locale)
    {
        $id = Auth::id();
        $backRoute = '/suggestion/' . $locale;

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
            ->with('rtlClass', $this->getRtlClass($locale))
            ->with('success', __('Your suggestion was successfully added!'))
            ->with('locale', $locale);
    }
}
