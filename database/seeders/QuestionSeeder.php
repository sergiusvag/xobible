<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $questions = json_decode(file_get_contents(asset('json/questions.json')), true);

        foreach($questions as $question) {
            DB::table('questions')->insert([
                'question'=>$question['question'],
                'option_1'=>$question['answers'][0],
                'option_2'=>$question['answers'][1],
                'option_3'=>$question['answers'][2],
                'option_4'=>$question['answers'][3],
                'answer'=>$question['correctAnswer'],
                'location'=>$question['origin'],
                'author_id'=>1,
            ]);
        }
    }
}
