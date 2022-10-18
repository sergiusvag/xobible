<?php

namespace App\Orchid\Resources;

use App\Orchid\Actions\DeleteAction;
use Orchid\Crud\Resource;
use Orchid\Crud\ResourceRequest;
use Illuminate\Database\Eloquent\Model;

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
