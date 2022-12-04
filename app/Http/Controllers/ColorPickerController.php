<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ColorPickerController extends Controller
{
    public function getDefaultData($isOnline) {
        $data = [
            'isOnline' => $isOnline,
            'playersBtnClass' => ['', ''],
            'numOfPlayers' => 2,
            'colors' => ["red", "green", "blue", "pink", "orange"],
            'playerNum' => ["one", "two"],
            'playerTitleText' => [__('Player One'), __('Player Two')],
            'playerSymbol' => ["x", "o"],
        ];

        return $data;
    }
}