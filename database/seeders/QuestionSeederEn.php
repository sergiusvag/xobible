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
            $allFieldsSet = true;
            foreach ($question as $keyQ => $valueQ) {
                if(empty($valueQ)) {
                    $allFieldsSet = false;
                }
            }
            $confirmed = $allFieldsSet;

            $id = $key + 1;
            DB::table('category_question')->insert([
                'question_id'=>$id,
                'category_id'=>1,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ]);
            DB::table('questions')->insert([
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
                'confirmed'=>false,
                'question_id'=>$id,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ]);
        }
    }
}
