<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //insert seeder teacher
        $teacher = [
            'name' => 'teacher',
            'username' => 'teacher68',
            'email' => 'teacher@gmail.com',
            'password' => Hash::make('123asdf123'),
            'type' => 'teacher'
        ];
        $insert_teacher = User::create($teacher);

        //insert seeder student
        $student = [
            'name' => 'student',
            'username' => 'student68',
            'email' => 'student@gmail.com',
            'password' => Hash::make('123asdf123'),
            'is_online' => 0,
            'type' => 'student'
        ];
        $insert_student = User::create($student);
    }
}
