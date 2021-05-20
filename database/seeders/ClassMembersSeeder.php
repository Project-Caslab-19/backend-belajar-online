<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Classroom;
use App\Models\ClassMember;
use App\Models\User;

class ClassMembersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $classrooms = Classroom::all();
        $users = User::where('type', 'student')->get();

        foreach ($classrooms as $key => $classroom) {
            foreach ($users as $user) {
                ClassMember::create([
                    'class_id' => $classroom->id,
                    'user_id' => $user->id,
                    'created_at' => Date('Y-m-d H:i:s')
                ]);
            }
        }
    }
}
