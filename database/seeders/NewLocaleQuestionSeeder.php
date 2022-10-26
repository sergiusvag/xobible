<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Question;

class NewLocaleQuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * This seeder is for all new locales if any will be added later to the game
     * @return void
     */
    public function run()
    {
        $questions = Question::all();

        // Example for a new table name
        // $newLocaleQuestionTableName = 'questions_ar';
        $newLocaleQuestionTableName = '';

        foreach($questions as $question) {
            DB::table($newLocaleQuestionTableName)->insert([
                'author_id' => $question->author_id,
                'question_id' => $question->id,
            ]);
        }
    }
}
