<?php

namespace App\Orchid\Resources;

use App\Orchid\Actions\DeleteAction;
use Orchid\Crud\Resource;
use Orchid\Crud\ResourceRequest;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\Sight;

// Extand this class only if you have an author_id in your model
abstract class BaseResource extends Resource
{
    public static function localeArr()
    {
        $localeArr =  [];

        foreach(config('app.available_locales') as $langName => $locale) {
            $localeArr[$langName] = ucfirst($locale);
        }

        return $localeArr;
    }
}