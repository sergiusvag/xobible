<?php

namespace App\Orchid\Resources;

use App\Orchid\Resources\AuthorableResource;
use App\Models\Question;
use App\Models\User;
use App\Models\Mistake;
use Illuminate\Database\Eloquent\Model;
use Orchid\Crud\ResourceRequest;
use Orchid\Crud\Resource;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\TD;
use Orchid\Screen\Sight;
use Orchid\Screen\Actions\Menu;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\CheckBox;

class QuestionResource extends AuthorableResource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = Question::class;

    /**
     * Get the fields displayed by the resource.
     *
     * @return array
     */
    public function fields(): array
    {
        $id = request()->route('id');
        $mistakes = $this::$model::find($id)->mistakes;
        $fields = [
            Input::make('question')
                ->title(__('Question'))
                ->required(),
            Group::make([
                Input::make('option_1')
                ->title(__('Possible answer'). ' 1')
                ->required(),
                Input::make('option_2')
                ->title(__('Possible answer'). ' 2')
                ->required(),
                Input::make('option_3')
                ->title(__('Possible answer'). ' 3')
                ->required(),
                Input::make('option_4')
                ->title(__('Possible answer'). ' 4')
                ->required(),
            ]),
            Input::make('answer')
                ->title(__('Correct answer'))
                ->required(),
            Input::make('location')
                ->title(__('Answer location in the Bible'))
                ->required(),
            Input::make('author_id')->type('hidden'),
        ];

        if(isset($mistakes)) {
            $mistakeDeleteOptions = [];

            foreach($mistakes as $mistake) {
                array_push($mistakeDeleteOptions,
                    CheckBox::make('mistakesToDelete.'.$mistake->id)
                        ->value(0)
                        ->placeholder(
                                __('Delete mistake №'). $mistake->id 
                                . ' | ' . __('Mistake author:') . ' ' . $mistake->author->name
                                . ' | ' . __('Mistake:') . ' ' . $mistake->mistake
                            )
                        ->sendTrueOrFalse()
                );
            }

            $fields = array_merge($fields,$mistakeDeleteOptions);
        };

        return $fields;
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
            TD::make('question', __('question'))->width('250px'),
            TD::make('options', __('options'))
                ->render(function ($question) {
                    return Group::make([
                        '1: '.$question['option_1'].'<br>'.
                        '2: '.$question['option_2'].'<br>'.
                        '3: '.$question['option_3'].'<br>'.
                        '4: '.$question['option_4']
                    ]);
                })
                ->width('250px'),
            TD::make('answer', __('answer'))->width('150px'),
            TD::make('location', __('location')),
            TD::make('author_id', __('author'))
                ->render(function ($question) {
                    return $question->author->name;
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
        $mistakes = $this::$model::find($id)->mistakes;
        $sightFields = [
            Sight::make('id'),
            Sight::make('question', __('Question')),
            Sight::make('option_1', __('Option').' 1'),
            Sight::make('option_2', __('Option').' 2'),
            Sight::make('option_3', __('Option').' 3'),
            Sight::make('option_4', __('Option').' 4'),
            Sight::make('answer', __('Answer')),
            Sight::make('location', __('Location')),
            Sight::make('author_id', __('Author'))
                ->render(function ($question) {
                    return $question->author->name;
                }),
        ];

        if(isset($mistakes)) {
            foreach($mistakes as $mistake) {
                array_push($sightFields, 
                    Sight::make('Mistake №:', __('Mistake') . ' №' . $mistake->id),
                    Sight::make('mistake', __('Mistake:') . ' ' . $mistake->mistake),
                    Sight::make('mistake author', __('Mistake author:') . ' ' . $mistake->author->name),
                );
            }
        };

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

    public function doBeforeSave(ResourceRequest $request, Model $model, Array $fields) {
        $fieldsForModel = [
            "question" => $fields["question"],
            "option_1" => $fields["option_1"],
            "option_2" => $fields["option_2"],
            "option_3" => $fields["option_3"],
            "option_4" => $fields["option_4"],
            "answer" => $fields["answer"],
            "location" => $fields["location"],
            "author_id" => $fields["author_id"],
        ];
        
        if(array_key_exists("mistakesToDelete", $fields)) {
            foreach($fields["mistakesToDelete"] as $id => $toDelete) {
                if($toDelete === "1") {
                    Mistake::destroy($id);
                }
            }
        }

        return [
            'fields' => $fieldsForModel,
            'none_model_fields' => [
                "mistakesToDelete" => $fields["mistakesToDelete"]
            ],
        ];
    }

    public static function perPage(): int
    {
        return 20;
    }

    public static function label(): string
    {
        return __('Questions');
    }

    public static function singularLabel(): string
    {
        return __('Question');
    }

    public static function createBreadcrumbsMessage(): string
    {
        return __('New Question');
    }

    public static function editBreadcrumbsMessage(): string
    {
        return __('Edit Question');
    }

    public static function createButtonLabel(): string
    {
        return __('Create Question');
    }

    public static function updateButtonLabel(): string
    {
        return __('Update Question');
    }
    
    public static function createToastMessage(): string
    {
        return __('The question was created!');
    }

    public static function updateToastMessage(): string
    {
        return __('The question was updated!');
    }

    public static function deleteToastMessage(): string
    {
        return __('The question was deleted!');
    }
}
