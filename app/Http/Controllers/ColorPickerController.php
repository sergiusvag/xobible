<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\models\Category;

class ColorPickerController extends BaseController
{
    public function getDefaultData($isOnline) {
        $locale = ucfirst(app()->getLocale());
        // $dbCategories = Category::where('available', 1)->get();
        $dbCategories = Category::all();
        $data = [
            'isOnline' => $isOnline,
            'playersBtnClass' => ['', ''],
            'numOfPlayers' => 2,
            'colors' => ["red", "green", "blue", "pink", "orange"],
            'playerNum' => ["one", "two"],
            'playerTitleText' => [__('Player One'), __('Player Two')],
            'playerSymbol' => ["x", "o"],
        ];
        $categories = [];
        $categoriesId = [];
        foreach($dbCategories as $category) {
            $localCategory = $category['Category'.$locale];
            if($localCategory->available === 1) {
                array_push($categories, $localCategory->name);
                array_push($categoriesId, $category->id);
            }
        }
        $data['categories'] = $categories;
        $data['categories_id'] = $categoriesId;
        return $data;
    }
}