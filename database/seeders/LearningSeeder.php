<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Topic;
use App\Models\Learning;

class LearningSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $topics = Topic::where('type', 'learning')->get();
        foreach ($topics as $key => $topic) {
            Learning::create([
                'topic_id' => $topic->id,
                'name' => $topic->name,
                'description' => $topic->description,
                'video' => 'video.mp4',
                'duration' => '0094783',
                'created_at' => Date('Y-m-d H:i:s')
            ]);
        }
    }
}
