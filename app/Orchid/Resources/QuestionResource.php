<?php

namespace App\Orchid\Resources;

use App\Orchid\Resources\AuthorableResource;
use App\Models\Category;
use App\Models\Question;
use App\Models\QuestionCategory;
use App\Models\QuestionEn;
use App\Models\QuestionRu;
use App\Models\QuestionHe;
use App\Models\User;
use App\Models\Mistake;
use Illuminate\Support\Facades\App;
use Illuminate\Database\Eloquent\Model;
use Orchid\Crud\ResourceRequest;
use Orchid\Crud\Resource;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\TD;
use Orchid\Screen\Sight;
use Orchid\Screen\Actions\Menu;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
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
        $localeArr = parent::localeArr();
        $id = request()->route('id');
        $question = $this::$model::find($id);
        $fields = [];

        if(isset($question) && !$question->mistakes->isEmpty()) {
            $fields = $this::createAndMergeMistakesFields($fields, $question->mistakes);
        };
        $locale = App::currentLocale();
        $categoryClass = 'App\Models\Category'.$locale;
        $questionCategory = QuestionCategory::where('question_id',$id)->get();
        $categories = [];
        for($i = 0; $i < count($questionCategory); $i ++){
            $categories[$i] = $questionCategory[$i]->category_id;
        }
        $questionFields = [
            Input::make('author_id')->type('hidden'),
            Select::make('categories_all')
                    ->title(__('Categories'))
                    ->multiple()
                    ->fromModel($categoryClass, 'name')
                    ->value($categories)
                    ->required()
        ];
        $fields = array_merge($fields,$questionFields);

        foreach($localeArr as $langName => $locale) {
            $fields = isset($question) ? $this::createAndMergeLocaleFields($fields, $question['question' . $locale], $locale)
                                    : $this::createAndMergeLocaleFields($fields, null, $locale);
        }

        return $fields;
    }

    public function createAndMergeMistakesFields($initFields, $mistakes) {
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

        return array_merge($initFields,$mistakeDeleteOptions);
    }

    public function createAndMergeLocaleFields($initFields, $question, $locale) {
        $fields = [];
        $localeUp = strtoupper($locale);
        if(isset($question)) {
            $fields = [
                Input::make('question_'.$locale)
                    ->title($localeUp . ' ' . __('Question'))
                    ->value($question->question),
                Group::make([
                    Input::make('option_1_'.$locale)
                    ->title($localeUp . ' ' . __('Possible answer'). ' 1')
                    ->value($question->option_1),
                    Input::make('option_2_'.$locale)
                    ->title($localeUp . ' ' . __('Possible answer'). ' 2')
                    ->value($question->option_2),
                    Input::make('option_3_'.$locale)
                    ->title($localeUp . ' ' . __('Possible answer'). ' 3')
                    ->value($question->option_3),
                    Input::make('option_4_'.$locale)
                    ->title($localeUp . ' ' . __('Possible answer'). ' 4')
                    ->value($question->option_4),
                ]),
                Input::make('answer_'.$locale)
                    ->title($localeUp . ' ' . __('Correct answer'))
                    ->value($question->answer),
                Input::make('location_'.$locale)
                    ->title($localeUp . ' ' . __('Answer location in the Bible'))
                    ->value($question->location),
            ];
        } else {
            $fields = [
                Input::make('question_'.$locale)
                    ->title($localeUp . ' ' . __('Question')),
                Group::make([
                    Input::make('option_1_'.$locale)
                    ->title($localeUp . ' ' . __('Possible answer'). ' 1'),
                    Input::make('option_2_'.$locale)
                    ->title($localeUp . ' ' . __('Possible answer'). ' 2'),
                    Input::make('option_3_'.$locale)
                    ->title($localeUp . ' ' . __('Possible answer'). ' 3'),
                    Input::make('option_4_'.$locale)
                    ->title($localeUp . ' ' . __('Possible answer'). ' 4'),
                ]),
                Input::make('answer_'.$locale)
                    ->title($localeUp . ' ' . __('Correct answer')),
                Input::make('location_'.$locale)
                    ->title($localeUp . ' ' . __('Answer location in the Bible')),
            ];
        }

        return array_merge($initFields,$fields);
    }
    /**
     * Get the columns displayed by the resource.
     *
     * @return TD[]
     */
    public function columns(): array
    {
        $localeArr = parent::localeArr();

        return [
            TD::make('id'),
            TD::make('question', __('Question'))
                ->render(function ($question) use ($localeArr) {
                    $questionString = '';

                    foreach($localeArr as $langName => $locale) {
                        $questionString = $questionString . '<br>'
                                         . $locale . ': ' . $question['question' . $locale]->question;
                    }

                    return Group::make([$questionString]);
                })
                ->width('500px'),
            TD::make('translated', __('Translation Status'))
                ->render(function ($question) use ($localeArr) {
                    $confirmedString = '';

                    foreach($localeArr as $langName => $locale) {
                        $isConfirmed = $question['question' . $locale]->confirmed ? __('Translated') : __('Not Translated');
                        $confirmedString = $confirmedString . '<br>'
                                        . $locale . ': ' . $isConfirmed;
                    }

                    return Group::make([$confirmedString]);
                }),
            TD::make('author_id', __('Author'))
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
        $localeArr = parent::localeArr();
        $id = request()->route('id');
        $question = $this::$model::find($id);
        $mistakes = $question->mistakes;
        $sightFields = [
            Sight::make('id', 'id : '),
            Sight::make('author_id', __('Author') . ' : ')->render(function($question) { return $question->author->name;}),
            Sight::make('categories', __('Categories') . ' : ')->render(function($question) { 
                $locale = ucfirst(App::currentLocale());
                
                $categories = $question->categories;
                $categoryString = $categories[0]['category' . $locale]->name;
                for($i = 1; $i < count($categories); $i++) {
                    $categoryString = $categoryString . ', ' . $categories[$i]['category' . $locale]->name;
                }
                return $categoryString;
            }),
        ];

        foreach($localeArr as $langName => $locale) {
            $sightFields = $this::createAndMergeLangSight($question['question' . $locale], $sightFields, __($langName));
        }

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

    public function extractFields($allFields, $locale) {
        $localeFields = [
            "question" => $allFields["question_" . $locale],
            "option_1" => $allFields["option_1_" . $locale],
            "option_2" => $allFields["option_2_" . $locale],
            "option_3" => $allFields["option_3_" . $locale],
            "option_4" => $allFields["option_4_" . $locale],
            "answer" => $allFields["answer_" . $locale],
            "location" => $allFields["location_" . $locale],
            "author_id" => $allFields["author_id"],
        ];

        $allFieldsSet = true;
        foreach ($localeFields as $key => $value) {
            if(empty($value)) {
                $allFieldsSet = false;
            }
        }
        $localeFields['confirmed'] = $allFieldsSet;

        return $localeFields;
    }

    public function doBeforeSave(ResourceRequest $request, Model $model, Array $fields) {
        if(array_key_exists("mistakesToDelete", $fields)) {
            foreach($fields["mistakesToDelete"] as $id => $toDelete) {
                if($toDelete === "1") {
                    Mistake::destroy($id);
                }
            }
        }
        
        $localeArr = parent::localeArr();
        $allFields = [];
        $noneModelFields = ['author_id' => $fields["author_id"], 'categories' => $fields["categories_all"]];
        foreach($localeArr as $langName => $locale) {
            $allFields[$locale] = $this::extractFields($fields, $locale);
        }
        return [
            'fields' => $allFields,
            'none_model_fields' => $noneModelFields,
        ];
    }

    public function saveCategories($questionId, $categories) {
        QuestionCategory::where('question_id',$questionId)->delete();

        foreach($categories as $category) {
            $QuestionCategory = new QuestionCategory([
                'category_id' => $category,
                'question_id' => $questionId,
            ]);
    
            $QuestionCategory->save();
        }
    }
    public function CustomSave(ResourceRequest $request, Model $model, Array $fields, Array $noneModelFields) {
        $localeArr = parent::localeArr();
        $questionNew = $model->forceFill(["author_id" => $noneModelFields['author_id']])->save();
        foreach($localeArr as $langName => $locale) {
            $fields[$locale]['question_id'] = $model->id;
            
            if($model['question'.$locale]) {
                $model['question'.$locale]->forceFill($fields[$locale])->save();
            } else {
                $questionClass = 'App\Models\Question'.$locale;
                $question = new $questionClass($fields[$locale]);
                $question->save();
            }
        }
        $this->saveCategories($model->id, $noneModelFields["categories"]);
    }

    public function onDelete(Model $model) {
        $localeArr = parent::localeArr();
        QuestionCategory::where('question_id',$model->id)->delete();

        foreach($localeArr as $locale) {
            $model['question'.$locale]->delete();
        }
        $model->delete();
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
    
    public static function deleteButtonLabel(): string
    {
        return __('Delete Question');
    }
}
