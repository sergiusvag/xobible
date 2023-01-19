<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuestionSeederHe extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $questions = json_decode(file_get_contents(asset('json/questionsHe.json')), true);

        foreach($questions as $key => $question) {
            $allFieldsSet = true;
            foreach ($question as $keyQ => $valueQ) {
                if(empty($valueQ)) {
                    $allFieldsSet = false;
                }
            }
            $confirmed = $allFieldsSet;

            DB::table('questions_he')->insert([
                'question'=>$question['question'],
                'option_1'=>$question['options'][0],
                'option_2'=>$question['options'][1],
                'option_3'=>$question['options'][2],
                'option_4'=>$question['options'][3],
                'answer'=>$question['answer'],
                'location'=>$question['location'],
                'author_id'=>1,
                'confirmed'=>$confirmed,
                'question_id'=>$key + 1,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ]);
        }
    }
}
