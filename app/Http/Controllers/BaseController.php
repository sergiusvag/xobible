<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BaseController extends Controller
{
    public function getRtlClass ($locale) {
        return $locale === 'he' ? 'input-rtl' : '';
    }

    public function getAudioData ($page) {
        $data = [];
        switch ($page) {
            case 'room':
                $data = ['message'];
                break;
            case 'color-picker':
                $data = [
                    'transition',
                    'select-selected',
                    'color-select',
                    'transition-out',
                    'color-error'
                ];
                break;
            case 'online-game':
            case 'offline-game':
                $data = [
                    'transition',
                    'select-selected',
                    'result-correct',
                    'result-wrong',
                    'question-select',
                ];
                break;
            default:
          }

          return $data;
    }
}