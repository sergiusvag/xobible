<?php

namespace App\Orchid\Resources;

use Orchid\Crud\Resource;
use Orchid\Screen\TD;
use Orchid\Screen\Sight;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;

class QuestionType extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\QuestionType::class;

    /**
     * Get the fields displayed by the resource.
     *
     * @return array
     */
    public function fields(): array
    {
        return [
                Input::make('type', __('Type')),
                Select::make('available')
                        ->title(__('Availability'))
                        ->options(["1" => __('Available'), "2" => __('Not available')])
            ];
    }

    /**
     * Get the columns displayed by the resource.
     *
     * @return TD[]
     */
    public function columns(): array
    {
        return [
            TD::make('id'),

            TD::make('type'),
            TD::make('available', __('Availability'))
                ->render(function ($questionType) {
                    return $questionType->available === 1 ? __('Available') : __('Not available');
                }),
        ];
    }

    /**
     * Get the sights displayed by the resource.
     *
     * @return Sight[]
     */
    public function legend(): array
    {
        return [
            Sight::make('id', 'id : '),
            Sight::make('type', __('Type : ')),
            Sight::make('available', __('Availability : '))
                ->render(function($questionType) { 
                    return $questionType->available === 1 ? __('Available') : __('Not available'); 
                }),
        ];
    }

    /**
     * Get the filters available for the resource.
     *
     * @return array
     */
    public function filters(): array
    {
        return [];
    }
    
    public static function label(): string
    {
        return __('Question Types');
    }

    public static function singularLabel(): string
    {
        return __('Question Type');
    }

    public static function createBreadcrumbsMessage(): string
    {
        return __('New Question Type');
    }

    public static function editBreadcrumbsMessage(): string
    {
        return __('Edit Question Type');
    }

    public static function createButtonLabel(): string
    {
        return __('Create Question Type');
    }

    public static function updateButtonLabel(): string
    {
        return __('Update Question Type');
    }
    
    public static function createToastMessage(): string
    {
        return __('The question type was created!');
    }

    public static function updateToastMessage(): string
    {
        return __('The question type was updated!');
    }

    public static function deleteToastMessage(): string
    {
        return __('The question type was deleted!');
    }
    
    public static function deleteButtonLabel(): string
    {
        return __('Delete Question Type');
    }
}
