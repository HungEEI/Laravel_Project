<?php

namespace Modules\Students\seeders;

use Faker\Factory;
use Illuminate\Database\Seeder;
use Modules\Students\src\Models\Student;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Factory::create();
        for ($i = 1; $i <= 30; $i++) {
            $student = new Student();
            $student->name = $faker->name;
            $student->email = $faker->email;
            $student->password = Hash::make('123456');
            $student->group_id = 1;
            $student->save();
        }
    }
}
