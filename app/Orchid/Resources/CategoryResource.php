<?php

namespace App\Orchid\Resources;

use App\Models\QuestionCategory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Crud\Resource;
use Orchid\Crud\ResourceRequest;
use Orchid\Screen\TD;
use Orchid\Screen\Sight;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\Group;

class CategoryResource extends BaseResource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Category::class;

    /**
     * Get the fields displayed by the resource.
     *
     * @return array
     */

     public function createAndMergeLocaleFields($initFields, $category, $locale) {
        $fields = [];
        $localeUp = strtoupper($locale);

        if(isset($category)) {
            $fields = [
                Input::make('name_'.$locale)
                    ->title($localeUp . ' ' . __('Name'))
                    ->value($category->name),
                Select::make('available_'.$locale)
                        ->title($localeUp . ' ' . __('Availability'))
                        ->options(["1" => __('Available'), "0" => __('Not available')])
                        ->value($category->available)
            ];
        } else {
            $fields = [
                Input::make('name_'.$locale)
                    ->title($localeUp . ' ' . __('Name')),
                Select::make('available_'.$locale)
                        ->title($localeUp . ' ' . __('Availability'))
                        ->options(["1" => __('Available'), "0" => __('Not available')])
            ];
        }

        return array_merge($initFields,$fields);
    }

    public function fields(): array
    {
        $localeArr = parent::localeArr();
        $id = request()->route('id');
        $category = $this::$model::find($id);
        $fields = [];

        foreach($localeArr as $langName => $locale) {
            $fields = isset($category) ? $this::createAndMergeLocaleFields($fields, $category['category' . $locale], $locale)
                                    : $this::createAndMergeLocaleFields($fields, null, $locale);
        }

        return $fields;
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
            TD::make('name', __('Name'))
                ->render(function ($category) use ($localeArr) {
                    $categoryString = '';
                    foreach($localeArr as $langName => $locale) {
                        $categoryString = $categoryString . '<br>'
                                         . $locale . ': ' . $category['category' . $locale]->name;
                    }

                    return Group::make([$categoryString]);
                }),
            TD::make('available', __('Availability'))
                ->render(function ($category) use ($localeArr) {
                    $categoryString = '';
                    foreach($localeArr as $langName => $locale) {
                        $available = $category['category' . $locale]->available === 1 ? __('Available') : __('Not available');
                        $categoryString = $categoryString . '<br>'
                                        . $locale . ': ' . $available;
                    }

                    return Group::make([$categoryString]);
                }),
        ];
    }

    public function createAndMergeSight($category, $initSights, $title) {
        $sights = [
            Sight::make('divider', ""),
            Sight::make('divider', "")->render(function() use($title) { return $title;}),
            Sight::make('divider', ""),
            Sight::make('name', __('Name : '))->render(function() use($category) { return $category->name;}),
            Sight::make('available', __('Availability : '))->render(function() use($category) { 
                return $category->available === 1 ? __('Available') : __('Not available');
            }),
        ];

        return array_merge($initSights,$sights);
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
        $category = $this::$model::find($id);
        $sights = [Sight::make('id', 'id : ')];
        
        foreach($localeArr as $langName => $locale) {
            $sights = $this->createAndMergeSight($category['category' . $locale], $sights, __($langName));
        }

        return $sights;
    }

    public function onSave(ResourceRequest $request, Model $model): void
    {
        $localeArr = parent::localeArr();
        $modelCreated = $model->forceFill([])->save();
        $allFields = $request->all();
        foreach($localeArr as $langName => $locale) {
            $fields = [
                'name' => $allFields['name_'.$locale],
                'available' => $allFields['available_'.$locale],
                'category_id' => $model->id,
            ];

            if($model['category'.$locale]) {
                $model['category'.$locale]->forceFill($fields)->save();
            } else {
                $categoryClass = 'App\Models\Category'.$locale;
                $category = new $categoryClass($fields);
                $category->save();
            }
        }
    }
    
    public function onDelete(Model $model) {
        $localeArr = parent::localeArr();
        QuestionCategory::where('category_id',$model->id)->delete();

        foreach($localeArr as $locale) {
            $model['category'.$locale]->delete();
        }
        $model->delete();
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
    
    public static function perPage(): int
    {
        return 20;
    }
    
    public static function label(): string
    {
        return __('Categories');
    }

    public static function singularLabel(): string
    {
        return __('Category');
    }

    public static function createBreadcrumbsMessage(): string
    {
        return __('New Category');
    }

    public static function editBreadcrumbsMessage(): string
    {
        return __('Edit Category');
    }

    public static function createButtonLabel(): string
    {
        return __('Create Category');
    }

    public static function updateButtonLabel(): string
    {
        return __('Update Category');
    }
    
    public static function createToastMessage(): string
    {
        return __('The category was created!');
    }

    public static function updateToastMessage(): string
    {
        return __('The category was updated!');
    }

    public static function deleteToastMessage(): string
    {
        return __('The category was deleted!');
    }
    
    public static function deleteButtonLabel(): string
    {
        return __('Delete Category');
    }
}
