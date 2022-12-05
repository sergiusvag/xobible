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
        $localeArr = parent::localeArr();

        $sightFields = [
            Sight::make('id', 'id : '),
            Sight::make('mistake', __('Mistake :')),
            Sight::make('author_id', __('Author :')),
            Sight::make('question_id', __('Question № :')),
            Sight::make('divider', ""),
            Sight::make('link to question', '')
                ->render(function ($mistake) {
                    $linkToQuestion = __('Link to question');

                    return '<a style="color:blue" href="/admin/crud/edit/question-resources/'.$mistake->question->id.'">'.$linkToQuestion.'</a>';
                }),
        ];
        foreach($localeArr as $langName => $locale) {
            $sightFields = $this::createAndMergeLangSight($mistake->question['question' . $locale], $sightFields, __($langName));
        }

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

    public static function deleteButtonLabel(): string
    {
        return __('Delete Mistake');
    }
}
