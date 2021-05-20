<?php

namespace Database\Seeders;

use App\Models\ClassMember;
use App\Models\Learning;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UsersTableSeeder::class,
            CategoriesTableSeeder::class,
            ClassroomSeeder::class,
            ClassMembersSeeder::class,
            LearningSeeder::class,
            TopicsSeeder::class,
            QuizSeeder::class,
            QuestionSeeder::class,
            AnswersSeeder::class
        ]);
    }
}
