<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Classroom;
use App\Models\Topic;

class TopicsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $classrooms = Classroom::all();
        $quiz = [
            'name' => 'Quiz',
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla vel odio non justo aliquet auctor. Sed vitae maximus magna. Phasellus nec suscipit ligula. Duis sollicitudin eu risus non bibendum. In accumsan massa eu ipsum sollicitudin, a condimentum arcu suscipit. Vivamus accumsan sagittis metus nec varius. Aliquam erat volutpat. Sed laoreet pharetra ornare. Nunc mi odio, convallis vitae porttitor sed, egestas sed odio. Nullam malesuada sagittis odio.
                            Vestibulum aliquet nisi at sodales placerat. Cras congue, nisl vel dignissim accumsan, diam risus vestibulum sem, tempor faucibus mi lorem nec turpis. Fusce malesuada sodales molestie. Proin dui arcu, fringilla non mi at, tempus scelerisque tortor. Curabitur vel pretium mi, quis tempus metus. In feugiat, nulla at vehicula consequat, nisi dui varius lacus, nec scelerisque purus nulla et tellus. Nam a viverra sapien.
                            Praesent ac ante efficitur, ullamcorper arcu sed, condimentum sem. In vel imperdiet metus. Fusce vel placerat nunc. Donec dui felis, pellentesque at purus et, venenatis condimentum ex.',
            'type' => 'quiz',
            'created_at' => Date('Y-m-d H:i:s')
        ];

        $learning = [
            'name' => 'Learning',
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla vel odio non justo aliquet auctor. Sed vitae maximus magna. Phasellus nec suscipit ligula. Duis sollicitudin eu risus non bibendum. In accumsan massa eu ipsum sollicitudin, a condimentum arcu suscipit. Vivamus accumsan sagittis metus nec varius. Aliquam erat volutpat. Sed laoreet pharetra ornare. Nunc mi odio, convallis vitae porttitor sed, egestas sed odio. Nullam malesuada sagittis odio.
                            Vestibulum aliquet nisi at sodales placerat. Cras congue, nisl vel dignissim accumsan, diam risus vestibulum sem, tempor faucibus mi lorem nec turpis. Fusce malesuada sodales molestie. Proin dui arcu, fringilla non mi at, tempus scelerisque tortor. Curabitur vel pretium mi, quis tempus metus. In feugiat, nulla at vehicula consequat, nisi dui varius lacus, nec scelerisque purus nulla et tellus. Nam a viverra sapien.
                            Praesent ac ante efficitur, ullamcorper arcu sed, condimentum sem. In vel imperdiet metus. Fusce vel placerat nunc. Donec dui felis, pellentesque at purus et, venenatis condimentum ex.',
            'type' => 'learning',
            'created_at' => Date('Y-m-d H:i:s')
        ];

        foreach ($classrooms as $key => $classroom) {
            //insert learning
            for($i = 0; $i <= 8; $i++)
            {
                Topic::create([
                    'class_id' => $classroom->id,
                    'name' => $learning['name'].' '.$i,
                    'description' => $learning['description'],
                    'type' => $learning['type'],
                    'created_at' => $learning['created_at']
                ]);
            }

            //insert quiz
            for($i = 0; $i <= 2; $i++)
            {
                Topic::create([
                    'class_id' => $classroom->id,
                    'name' => $quiz['name'].' '.$i,
                    'description' => $quiz['description'],
                    'type' => $quiz['type'],
                    'created_at' => $quiz['created_at']
                ]);
            }
        }
    }
}
