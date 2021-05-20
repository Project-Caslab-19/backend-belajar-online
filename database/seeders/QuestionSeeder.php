<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Quiz;
use App\Models\Question;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $quizzes = Quiz::all();

        foreach ($quizzes as $key => $quiz) {
            Question::create([
                'quiz_id' => $quiz->id,
                'value' => "pertanyaan $quiz->name",
                'created_at' => Date('Y-m-d H:i:s')
            ]);
        }
    }
}
