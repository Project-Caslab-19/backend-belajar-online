<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Question;
use App\Models\Answer;

class AnswersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $questions = Question::all();
        foreach ($questions as $key => $question) {
            $cek = Answer::where('question_id', $question->id)->where('key_answer', 1)->first();
            if(empty($cek))
            {
                Answer::create([
                    'question_id' => $question->id,
                    'value' => "Jawaban $question->value",
                    'key_answer' => '1'
                ]);
            }
            else
            {
                Answer::create([
                    'question_id' => $question->id,
                    'value' => "Jawaban $question->value",
                    'key_answer' => '0'
                ]);
            }
        }
    }
}
