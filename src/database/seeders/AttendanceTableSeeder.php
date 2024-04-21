<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str; //追加
use Illuminate\Support\Facades\Hash; //追加
use App\User;
use Faker\Provider\DateTime; // 追加


class AttendanceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         DB::table('attendances')->insert([
            [
                'start_time' => DateTime::dateTimeThisDecade(),
                'end_time'   => DateTime::dateTimeThisDecade(),
                'created_at' => DateTime::dateTimeThisDecade(),
                'updated_at' => DateTime::dateTimeThisDecade(),
            ],

        ]);
    }
}
