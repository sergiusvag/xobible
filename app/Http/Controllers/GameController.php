<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GameController extends Controller
{
    public function getRtlClass ($locale) {
        return $locale === 'he' ? 'input-rtl' : '';
    }
}