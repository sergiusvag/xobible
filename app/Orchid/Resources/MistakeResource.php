<?php

namespace App\Orchid\Resources;

use App\Orchid\Resources\AuthorableResource;
use App\Models\Mistake;
use App\Models\Question;
use App\Models\QuestionRu;
use App\Models\QuestionHe;
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
        $id = request()->route('id');
        $mistake = $this::$model::find($id);

        $sightFields = [
            Sight::make('id', 'id : ' . $mistake->id)->render(function() { return '';}),
            Sight::make('mistake', __('Mistake') . ' : '. $mistake->mistake)->render(function() { return '';}),
            Sight::make('author_id', __('Author') . ' : ' . $mistake->author->name)->render(function() { return '';}),
            Sight::make('question_id', __('Question') . ' № : ' . $mistake->question_id)->render(function() { return '';}),
            Sight::make('link to question', '')
                ->render(function ($mistake) {
                    $linkToQuestion = __('Link to question');

                    return '<a style="color:blue" href="/admin/crud/edit/question-resources/'.$mistake->question->id.'">'.$linkToQuestion.'</a>';
                }),
        ];
        $sightFields = $this::createAndMergeLangSight($mistake->question, $sightFields, __('English'));
        $sightFields = $this::createAndMergeLangSight($mistake->question['questionRu'], $sightFields, __('Russian'));
        $sightFields = $this::createAndMergeLangSight($mistake->question['questionHe'], $sightFields, __('Hebrew'));

        return $sightFields;
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
