<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Attendance;
use App\Models\Rest;
use Faker\Provider\DateTime;


class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      // User::factory(3)
      //   ->create()
      //   ->each(function ($user) {
      //       $user->attendances()->save(factory(App\Attendance::class)->make());

      //   });

        $attendances = '';
        $users = User::factory(10)->create();
        foreach ($users as $user) {
          $attendances = $user->attendances()->saveMany(Attendance::factory()->count(15)->create(['user_id' => $user->id]));
        }
        foreach ($attendances as $attendance) {
          $attendance->rests()->saveMany(Rest::factory()->count(10)->create(['attendance_id' => $attendance->id]));
        }

      // for ($i = 0; $i < 50; $i++) {
      //   DB::table('users')->insert([
      //       'name' => Str::random(10),
      //       'email'     => Str::random(10).'@gmail.com',
      //       'password'  => Hash::make('password'),
      //   ]);
    

    // DB::table('rests')->insert([
    //         [
    //             'attendance_id' => Users::inRandomOrder()->first()->id,
    //             'start_time' => DateTime::dateTimeThisDecade(),
    //             'end_time'   => DateTime::dateTimeThisDecade(),
    //             'created_at' => DateTime::dateTimeThisDecade(), // 追加
    //             'updated_at' => DateTime::dateTimeThisDecade(),
    //         ],
    //     ]);

    //     DB::table('attendances')->insert([
    //         [
    //             'attendance_id' => Users::inRandomOrder()->first()->id,
    //             'start_time' => DateTime::dateTimeThisDecade(),
    //             'end_time'   => DateTime::dateTimeThisDecade(),
    //             'created_at' => DateTime::dateTimeThisDecade(), // 追加
    //             'updated_at' => DateTime::dateTimeThisDecade(),
    //         ],

        // ]);
}
}