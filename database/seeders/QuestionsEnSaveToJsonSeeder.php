<?php

namespace Database\Seeders;

use App\Models\Question;
use App\Models\QuestionHe;
use App\Models\QuestionRu;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class QuestionsEnSaveToJsonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $questions = Question::All();

        $dataToEncode = [];

        foreach($questions as $question) {
            array_push($dataToEncode, [
                'question' => $question['question'],
                'answers' => [$question['option_1'], $question['option_2'], $question['option_3'], $question['option_4']],
                'correctAnswer' => $question['question'],
                'origin' => $question['location'],
            ]);
        }

        $questionsEn = json_encode($dataToEncode, JSON_UNESCAPED_UNICODE);
        Storage::disk('public')->put('json/questionsEn.json', $questionsEn);
    }

    
//   {
//     "question": "Из какого племени Павел?",
//     "answers": ["Из Дана", "Из Вениамина", "Из Иуды", "Из Ефрема"],
//     "correctAnswer": "Из Вениамина",
//     "origin": "Филиппийцам 3:5"
//   },
}
