<?php

namespace App\Orchid\Resources;

use App\Orchid\Resources\AuthorableResource;
use App\Models\Suggestion;
use App\Models\Question;
use App\Models\QuestionRu;
use App\Models\QuestionHe;
use Illuminate\Database\Eloquent\Model;
use Orchid\Crud\Resource;
use Orchid\Crud\ResourceRequest;
use Orchid\Screen\TD;
use Orchid\Screen\Sight;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Select;

class SuggestionResource extends AuthorableResource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = Suggestion::class;

    /**
     * Get the fields displayed by the resource.
     *
     * @return array
     */
    public function fields(): array
    {
        return [
            Input::make('id')
                ->type('hidden'),
            Input::make('question')
                ->title(__('Question')),
            Group::make([
                Input::make('option_1')
                ->title(__('Possible answer'). ' 1'),
                Input::make('option_2')
                ->title(__('Possible answer'). ' 2'),
                Input::make('option_3')
                ->title(__('Possible answer'). ' 3'),
                Input::make('option_4')
                ->title(__('Possible answer'). ' 4'),
            ]),
            Input::make('answer')
                ->title(__('Correct answer')),
            Input::make('location')
                ->title(__('Answer location in the Bible')),
            Input::make('author_id')->type('hidden'),
            Select::make('addToQuestionsLocale')
                ->options([
                    'none'   => __('Do not add yet'),
                    'EN' => __('English'),
                    'RU' => __('Russian'),
                    'HE' => __('Hebrew'),
                ])
                ->title('Select tags'),
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
        $suggestion = $this::$model::find($id);
        $sightFields = [
            Sight::make('id', 'id : ' . $suggestion->id)->render(function() { return '';}),
            Sight::make('author_id', __('Author') . ' : ' . $suggestion->author->name)->render(function() { return '';}),
        ];
        
        $sightFields = $this::createAndMergeLangSight($suggestion, $sightFields, __('Suggestion'));
        
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
        
        if($fields["addToQuestionsLocale"] !== 'none')
        {
            $allFieldsSetEn = true;
            foreach ($fieldsForModel as $key => $value) {
                if(empty($value)) {
                    $allFieldsSetEn = false;
                }
            }
            $fieldsForModel['confirmed'] = $allFieldsSetEn;
        }

        return [
            'fields' => $fieldsForModel,
            'none_model_fields' => [
                "addToQuestionsLocale" => $fields["addToQuestionsLocale"],
                "id" => $fields["id"]
            ],
        ];
    }
    
    public function CustomSave(ResourceRequest $request, Model $model, Array $fields, Array $noneModelFields) {

        if($noneModelFields["addToQuestionsLocale"] !== 'none') {
                $questionModel;
                $fieldsToFill = [
                    'EN' => ['author_id' => $fields['author_id']],
                    'RU' => [],
                    'HE' => [],
                ];
                switch ($noneModelFields["addToQuestionsLocale"]) {
                    case "EN":
                        $fieldsToFill['EN'] = $fields;
                        break;
                    case "RU":
                        $fieldsToFill['RU'] = $fields;
                        break;
                    case "HE":
                        $fieldsToFill['HE'] = $fields;
                        break;
                }

                $question = new Question;
                $question->forceFill($fieldsToFill['EN'])->save();
                
                $fieldsToFill['RU']['question_id'] = $question->id;
                $fieldsToFill['RU']['author_id'] = $question->author_id;
                $questionRu = new QuestionRu;
                $questionRu->forceFill($fieldsToFill['RU'])->save();
                
                $fieldsToFill['HE']['question_id'] = $question->id;
                $fieldsToFill['HE']['author_id'] = $question->author_id;
                $questionHe = new QuestionHe;
                $questionHe->forceFill($fieldsToFill['HE'])->save();

                Suggestion::destroy($noneModelFields["id"]);
        } else {
            $model->forceFill($fields)->save();
        }
    }

    public static function label(): string
    {
        return __('Suggestions');
    }

    public static function singularLabel(): string
    {
        return __('Suggestion');
    }

    public static function createBreadcrumbsMessage(): string
    {
        return __('New Suggestion');
    }

    public static function editBreadcrumbsMessage(): string
    {
        return __('Edit Suggestion');
    }

    public static function createButtonLabel(): string
    {
        return __('Create Suggestion');
    }

    public static function updateButtonLabel(): string
    {
        return __('Update Suggestion');
    }
    
    public static function createToastMessage(): string
    {
        return __('The suggestion was created!');
    }

    public static function updateToastMessage(): string
    {
        return __('The suggestion was updated!');
    }

    public static function deleteToastMessage(): string
    {
        return __('The suggestion was deleted!');
    }
}
