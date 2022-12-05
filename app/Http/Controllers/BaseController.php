<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BaseController extends Controller
{
    public function getRtlClass ($locale) {
        return $locale === 'he' ? 'input-rtl' : '';
    }
}