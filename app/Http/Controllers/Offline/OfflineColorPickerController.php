<?php

namespace App\Http\Controllers\Offline;

use App\Models\Room;
use App\Models\GameStatus;
use App\Events\ColorEventStart;
use App\Http\Controllers\ColorPickerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OfflineColorPickerController extends ColorPickerController 
{
    public function index(Request $request, $locale) {
        $audioData = $this->getAudioData('color-picker');
        $data = $this->getDefaultData(false);
        return view('offline.offline-color-picker')
            ->with('data', $data)
            ->with('audioData', $audioData)
            ->with('locale', $locale);
    }
}