<?php

namespace App\Orchid\Resources;

use App\Orchid\Resources\AuthorableResource;
use App\Models\Mistake;
use Orchid\Crud\Resource;
use Orchid\Screen\TD;
use Orchid\Screen\Sight;
use Orchid\Screen\Fields\Input;

class MistakeResource extends AuthorableResource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = Mistake::class;

    /**
     * Get the fields displayed by the resource.
     *
     * @return array
     */
    public function fields(): array
    {
        return [
            Input::make('question_id')
                ->title(__('question_id'))
                ->help(__('or question number'))
                ->required(),
            Input::make('mistake')
                ->title(__('Mistake'))
                ->required(),
            Input::make('author_id')->type('hidden'),
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
            TD::make('question_id'),
            TD::make('mistake', __('mistake')),
            TD::make('author_id', __('author'))
                ->render(function ($mistake) {
                    return $mistake->author->name;
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
            Sight::make('id'),
            Sight::make('mistake', __('Mistake')),
            Sight::make('author_id', __('Author'))
                ->render(function ($mistake) {
                    return $mistake->author->name;
                }),
            Sight::make('question_id', __('Question') . ' №'),
            Sight::make('question', __('Question'))
                ->render(function ($mistake) {
                    return $mistake->question->question;
                }),
            Sight::make('options', __('Options'))
                ->render(function ($mistake) {
                    return '1: '.$mistake->question->option_1
                            .'<br>2: '.$mistake->question->option_2
                            .'<br>3: '.$mistake->question->option_3
                            .'<br>4: '.$mistake->question->option_4;
                }),
            Sight::make('answer', __('Answer'))
                ->render(function ($mistake) {
                    return $mistake->question->answer;
                }),
            Sight::make('location', __('Location'))
                ->render(function ($mistake) {
                    return $mistake->question->location;
                }),
            Sight::make('link to question', __('Link to question'))
                ->render(function ($mistake) {
                    $linkToQuestion = __('Link to question');

                    return '<a href="/admin/crud/edit/question-resources/'.$mistake->question->id.'">'.$linkToQuestion.'</a>';
                })
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
        return __('Mistakes');
    }

    public static function singularLabel(): string
    {
        return __('Mistake');
    }

    public static function createBreadcrumbsMessage(): string
    {
        return __('New Mistake');
    }

    public static function editBreadcrumbsMessage(): string
    {
        return __('Edit Mistake');
    }

    public static function createButtonLabel(): string
    {
        return __('Create Mistake');
    }

    public static function updateButtonLabel(): string
    {
        return __('Update Mistake');
    }
    
    public static function createToastMessage(): string
    {
        return __('The mistake was created!');
    }

    public static function updateToastMessage(): string
    {
        return __('The mistake was updated!');
    }

    public static function deleteToastMessage(): string
    {
        return __('The mistake was deleted!');
    }
}
