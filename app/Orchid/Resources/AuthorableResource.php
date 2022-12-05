<?php

namespace App\Orchid\Resources;

use App\Orchid\Actions\DeleteAction;
use Orchid\Crud\Resource;
use Orchid\Crud\ResourceRequest;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\Sight;

// Extand this class only if you have an author_id in your model
abstract class AuthorableResource extends Resource
{
    public static function localeArr()
    {
        $localeArr =  [];

        foreach(config('app.available_locales') as $langName => $locale) {
            $localeArr[$langName] = ucfirst($locale);
        }

        return $localeArr;
    }

    public function setFields(ResourceRequest $request) {
        $fields = $request->all();

        if(is_null($fields['author_id'])){
            $fields['author_id'] = auth()->id();
        }

        return $fields;
    }

    public function emptyFunc() { return '';}

    public function createAndMergeLangSight($question, $initSights, $title) {
        $sights = [
            Sight::make('divider', ""),
            Sight::make('divider', "")->render(function() use($title) { return $title;}),
            Sight::make('divider', ""),
            Sight::make('question', __('Question :'))->render(function() use($question) { return $question->question;}),
            Sight::make('option_1', __('Option 1 :'))->render(function() use($question) { return $question->option_1;}),
            Sight::make('option_2', __('Option 2 :'))->render(function() use($question) { return $question->option_2;}),
            Sight::make('option_3', __('Option 3 :'))->render(function() use($question) { return $question->option_3;}),
            Sight::make('option_4', __('Option 4 :'))->render(function() use($question) { return $question->option_4;}),
            Sight::make('answer', __('Answer :'))->render(function() use($question) { return $question->answer;}),
            Sight::make('location', __('Location :'))->render(function() use($question) { return $question->location;}),
        ];
            
        return array_merge($initSights,$sights);
    }

    public function doBeforeSave(ResourceRequest $request, Model $model, Array $fields) {
        return [
            'fields' => $fields,
            'none_model_fields' => [],
        ];
    }

    public function CustomSave(ResourceRequest $request, Model $model, Array $fields, Array $noneModelFields) {
        $model->forceFill($fields)->save();
    }

    public function onSave(ResourceRequest $request, Model $model): void
    {
        $fields = $this::setFields($request);
        $allFields = $this::doBeforeSave($request, $model, $fields);
        $this::CustomSave($request, $model, $allFields['fields'], $allFields['none_model_fields']);
    }
    
    public function actions(): array
    {
        return [
            DeleteAction::class,
        ];
    }
}
