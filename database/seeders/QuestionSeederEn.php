<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuestionSeederEn extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $questions = json_decode(file_get_contents(asset('json/questionsEn.json')), true);

        foreach($questions as $key => $question) {
            DB::table('questions')->insert([
                'question_type'=>1,
                'author_id'=>1,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ]);
            DB::table('questions_en')->insert([
                'question'=>$question['question'],
                'option_1'=>$question['options'][0],
                'option_2'=>$question['options'][1],
                'option_3'=>$question['options'][2],
                'option_4'=>$question['options'][3],
                'answer'=>$question['answer'],
                'location'=>$question['location'],
                'author_id'=>1,
                'confirmed'=>true,
                'question_id'=>$key + 1,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ]);
        }
    }
}
