<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Topic;
use App\Models\Quiz;

class QuizSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $topics = Topic::where('type', 'quiz')->get();
        foreach ($topics as $key => $topic) {
            Quiz::create([
                'topic_id' => $topic->id,
                'name' => $topic->name,
                'description' => $topic->description,
                'created_at' => Date('Y-m-d H:i:s')
            ]);
        }
    }
}
