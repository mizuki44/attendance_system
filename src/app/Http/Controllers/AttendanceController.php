<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Attendance;
use App\Models\Rest;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use DateTime;

class AttendanceController extends Controller
{
    // public function index()
    // {   
    //     var_dump('index!');
    //     //ログインページを表示
    //     return view('index');
    // }

//打刻ページを表示
    public function index()
    {
        if (Auth::check()) {
            $user = Auth::user();
            $oldAttendance = Attendance::where('user_id', $user->id)->latest()->first();
            if ($oldAttendance) {

                $isWorkStarted = $this->didWorkStart($user);
                // var_dump($isWorkStarted);
                $isWorkEnded = $this->didWorkEnd();
                $isRestStarted = $this->didRestStart();
            } else {
                $isWorkStarted = false;
                $isWorkEnded = false;
                $isRestStarted = false;
            }

            $param = [
                'user' => $user,
                'isWorkStarted' => $isWorkStarted,
                'isWorkEnded' => $isWorkEnded,
                'isRestStarted' => $isRestStarted,
            ];
            return view('/index', $param);
        } else {
            return redirect('/login');
        }
    }


       //「勤務開始」判定
    private function didWorkStart($user)
    {

        $oldAttendance = Attendance::where('user_id', $user->id)->latest()->first();

        if ($oldAttendance) {
            $oldAttendanceDay = new Carbon(($oldAttendance->created_at)->setTime(0,0,0));
            $today = Carbon::today();
            if ($oldAttendance){

            }
            return ($oldAttendanceDay == $today);
        } else {
            return false;

            // ここまでできた
        }
    }

      //出勤アクション
    public function workStart()
    {
        $user = Auth::user();
        //「勤務開始」判定（ボタンをアクティブにするかしないか）
        $isWorkStarted = $this->didWorkStart($user);

        //「勤務終了」判定
        $isWorkEnded = $this->didWorkEnd();

        Attendance::create([
            'user_id' => $user->id,
            // 'date' => Carbon::today(),
            'start_time' => Carbon::now(),
            'end_time' => null,
        ]);

        return redirect()->back();
    }

    //退勤アクション
    public function workEnd()
    {
        $user = Auth::user();
        $attendance = Attendance::where('user_id', $user->id)->latest()->first();

        if ($attendance) {
            if (is_null($attendance->end_time)) {
                $rest = Rest::where('attendance_id', $attendance->id)->latest()->first();
                if ($rest && $rest->start_time && !$rest->end_time) {
                    $rest->update([
                        'end_time' => Carbon::now(),
                    ]);
                }

                $attendance->update([
                    'end_time' => Carbon::now()
                ]);

                return redirect()->back();
            }
        } else {
            return redirect()->back();
        }
        return redirect()->back();
    }



//「勤務終了」判定（ここをやった）
    private function didWorkEnd()
    {
        $user = Auth::user();
        $attendance = Attendance::where('user_id', $user->id)->latest()->first();
        $oldDay = '';

        if ($attendance) {
            $oldDay = new Carbon(($attendance->created_at)->setTime(0,0,0));
        
            $today = Carbon::today();

            $oldRest = Rest::where('attendance_id', $attendance->id)->latest()->first();
            if ($oldRest) {
                //休憩を最低1度以上「開始」と「終了」を両方選択している
                return !($oldDay == $today && empty($attendance->end_time) && ($oldRest->end_time));
            }
        }
    }

    //「休憩中」判定
    private function didRestStart()
    {
        $user = Auth::user();
        $oldRest = '';
        $oldDay = '';

        if (Attendance::where('user_id', $user->id)->exists()) {
            $attendance = Attendance::where('user_id', $user->id)->latest()->first();

            if (Rest::where('attendance_id', $attendance->id)->exists()) {
                $oldRest = Rest::where('attendance_id', $attendance->id)->latest()->first();
            }

            if ($oldRest) {
                $oldRestStartTime = new Carbon($oldRest->start_time);
                $oldDay = $oldRestStartTime->startOfday();
            }

            $today = Carbon::today();

            //restsテーブルの最新のレコードが今日のデータ、かつ休憩終了がない（レコードがあるということは勤務開始＆休憩開始されている）
            return ($oldDay == $today) && (!$oldRest->end_time) && !($attendance->end_time);
        }
    }
// 判定の条件が複雑すぎて時間が経つとわからなくなってしまうのですが、綺麗に描くコツはあるのか？

//休憩開始アクション
    public function restStart()
    {
        $user = Auth::user();
        $attendance = Attendance::where('user_id', $user->id)->latest()->first();

        //「休憩中」判定
        $isRestStarted = $this->didRestStart();

        Rest::create([
            'attendance_id' => $attendance->id,
            'start_time' => Carbon::now(),
        ]);

        return redirect()->back()->with([
            'user' => $user,
            'isRestStarted' => $isRestStarted,
        ]);
    }
 //休憩終了アクション
    public function restEnd()
    {
        $user = Auth::user();
        $attendance = Attendance::where('user_id', $user->id)->latest()->first();
        $oldRest = Rest::where('attendance_id', $attendance->id)->latest()->first();

        $isRestStarted = $this->didRestStart();

        //end_timeが存在しない場合は、end_timeを格納
        if ($oldRest->start_time && !$oldRest->end_time) {
            $oldRest->update([
                'end_time' => Carbon::now(),
            ]);
        }

        return redirect()->back()->with([
            'user' => $user,
            'isRestStarted' => $isRestStarted,
        ]);
    }
}