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
    public function setFields(ResourceRequest $request) {
        $fields = $request->all();

        if(is_null($fields['author_id'])){
            $fields['author_id'] = auth()->id();
        }

        return $fields;
    }

    public function createAndMergeLangSight($question, $initSights, $title) {
        $sights = [
            Sight::make('divider', ""),
            Sight::make('divider', $title),
            Sight::make('question', __('Question') . ' : ' . $question->question)->render(function() { return '';}),
            Sight::make('option_1', __('Options') . ' 1 : ' . $question->option_1)->render(function() { return '';}),
            Sight::make('option_2', __('Option') . ' 2 : ' . $question->option_2)->render(function() { return '';}),
            Sight::make('option_3', __('Option') . ' 3 : ' . $question->option_3)->render(function() { return '';}),
            Sight::make('option_4', __('Option') . ' 4 : ' . $question->option_4)->render(function() { return '';}),
            Sight::make('answer', __('Answer')  . ' : ' . $question->answer)->render(function() { return '';}),
            Sight::make('location', __('Location')  . ' : ' . $question->location)->render(function() { return '';}),
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
