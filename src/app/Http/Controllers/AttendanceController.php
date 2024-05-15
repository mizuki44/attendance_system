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

    public function index()
    {
        if (Auth::check()) {
            $user = Auth::user();
            $oldAttendance = Attendance::where('user_id', $user->id)->latest()->first();
            if ($oldAttendance) {

                $isWorkStarted = $this->didWorkStart($user);
                $isWorkEnded = $this->didWorkEnd();
                $isRestStarted = $this->didRestStart();
                $isRestEnded = $this->didRestEnd();
            } else {
                $isWorkStarted = false;
                $isWorkEnded = false;
                $isRestStarted = false;
                $isRestEnded = false;
            }

            $param = [
                'user' => $user,
                'isWorkStarted' => $isWorkStarted,
                'isWorkEnded' => $isWorkEnded,
                'isRestStarted' => $isRestStarted,
                'isRestEnded' => $isRestEnded,
            ];
            return view('/index', $param);
        } else {
            return redirect('/login');
        }
    }


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
        }
    }

    public function workStart()
    {
        $user = Auth::user();
        $isWorkStarted = $this->didWorkStart($user);
        $isWorkEnded = $this->didWorkEnd();

        Attendance::create([
            'user_id' => $user->id,
            'date' => Carbon::today(),
            'start_time' => Carbon::now(),
            'end_time' => null,
        ]);

        return redirect()->back();
    }

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
                return !($oldDay == $today && empty($attendance->end_time) && ($oldRest->end_time));
            }
        }
    }

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

            return ($oldDay == $today) && empty($oldRest->end_time);
        }
    }


     private function didRestEnd()
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

            return ($oldDay == $today) && ($oldRest->end_time);
        }
    }

    public function restStart()
    {
        $user = Auth::user();
        $attendance = Attendance::where('user_id', $user->id)->latest()->first();


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

    public function restEnd()
    {
        $user = Auth::user();
        $attendance = Attendance::where('user_id', $user->id)->latest()->first();
        $oldRest = Rest::where('attendance_id', $attendance->id)->latest()->first();

        $isRestStarted = $this->didRestStart();

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




    private function actualWorkTime($attendanceToday, $restTimeDiffInSecondsTotal)
    {
        $attendanceStartTime = $attendanceToday->start_time;
        $attendanceStartTimeCarbon = new Carbon($attendanceToday->start_time);
        $attendanceEndTime = $attendanceToday->end_time;
        $attendanceEndTimeCarbon = new Carbon($attendanceToday->end_time);
        $workTimeDiffInSeconds = $attendanceEndTimeCarbon->diffInSeconds($attendanceStartTimeCarbon);
        $workTimeSeconds = floor($workTimeDiffInSeconds % 60);
        $workTimeMinutes = floor(($workTimeDiffInSeconds % 3600) / 60);
        $workTimeHours = floor($workTimeDiffInSeconds / 3600);
        $workTime = sprintf('%02d',$workTimeHours) . ":" . sprintf('%02d',$workTimeMinutes) . ":" . sprintf('%02d',$workTimeSeconds);

         $restTimeSeconds = floor($restTimeDiffInSecondsTotal % 60);
        $restTimeMinutes = floor(($restTimeDiffInSecondsTotal % 3600) / 60);
        $restTimeHours = floor($restTimeDiffInSecondsTotal / 3600);
        $restTime = sprintf('%02d',$restTimeHours) . ":" . sprintf('%02d',$restTimeMinutes) . ":" . sprintf('%02d',$restTimeSeconds);



        $actualWorkTimeDiffInSeconds = $workTimeDiffInSeconds - $restTimeDiffInSecondsTotal;

        $actualWorkTimeSeconds = floor($actualWorkTimeDiffInSeconds % 60);
        $actualWorkTimeMinutes = floor(($actualWorkTimeDiffInSeconds % 3600) / 60);
        $actualTimeHours = floor($actualWorkTimeDiffInSeconds  / 3600);
        $actualWorkTime = sprintf('%02d',$actualTimeHours) . ":" . sprintf('%02d',$actualWorkTimeMinutes) . ":" . sprintf('%02d',$actualWorkTimeSeconds);

               $userId = User::where('id', $attendanceToday->user_id)->first();
        $name = $userId->name;

        $date = $attendanceToday->date;


        $param = [
            'name' =>$name,
            'date'=>$date,
            'attendanceStartTime' => $attendanceStartTime,
            'attendanceEndTime' => $attendanceEndTime,
            'restTime' => $restTime,
            'actualWorkTime' => $actualWorkTime,
        ];

        return $param;
    }


    private function calculateRestTime($restToday)
    {
        $restStartTime = new Carbon($restToday->start_time);
        $restEndTime = new Carbon($restToday->end_time);
        $restTimeDiffInSeconds = $restEndTime->diffInSeconds($restStartTime);
         $restTimeSeconds = floor($restTimeDiffInSeconds % 60);
        $restTimeMinutes = floor(($restTimeDiffInSeconds % 3600) / 60);
        $restTimeHours = floor($restTimeDiffInSeconds / 3600);
        $restTime = sprintf('%02d',$restTimeHours) . ":" . sprintf('%02d',$restTimeMinutes) . ":" . sprintf('%02d',$restTimeSeconds);
        return $restTimeDiffInSeconds;
    }





    public function getAttendances(Request $request)
    {
        if (is_null($request->date)) {
            $yesterday = Carbon::yesterday();
            $today = Carbon::today();
            $tomorrow = Carbon::tomorrow();
        } else {
            $today = new Carbon($request->date);
            $yesterday = (new Carbon($request->date))->subDay();
            $tomorrow = (new Carbon($request->date))->addDay();
        }

        $resultArray[] = array();
        $i = 0;

        $attendanceTodayAll = Attendance::where('date', $today->format('Y-m-d'))->get();

        foreach ($attendanceTodayAll as $attendanceToday) {
            if ($attendanceToday->end_time) {
                $restTodayAll = Rest::where('attendance_id',
                $attendanceToday->id)->get();

                $restTimeDiffInSecondsTotal = 0;
                foreach ($restTodayAll as $restToday) {
                    $restTime = $this->calculateRestTime($restToday);
                    $restTimeDiffInSecondsTotal = $restTimeDiffInSecondsTotal + $restTime;
                }
                $result = $this->actualWorkTime($attendanceToday, $restTimeDiffInSecondsTotal);
                $resultArray[$i] = $result;
                $i++;
            }
        }

        $attendances = $this->paginate($resultArray, 5, null, ['path' => "/attendance_list?date={$today->format('Y-m-d')}"]);

        return view('/attendance_list')->with([
            'today' => $today,
            'yesterday' => $yesterday,
            'tomorrow' => $tomorrow,
            'attendances' => $attendances,
        ]);
    }

    private function paginate($items, $perPage, $page, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);

        return new LengthAwarePaginator(
            $items->forPage($page, $perPage),
            $items->count(),
            $perPage,
            $page,
            $options
        );
    }

    public function getUserList()
    {
        $getUsers = User::select('id','name', 'email')->get();

        $usersArray[] = array();
        $i = 0;
        foreach ($getUsers as $user) {
            $usersArray[$i] = $user;
            $i++;
        }
          $users = $this->paginate($usersArray, 5, null, ['path' => "/user_page"]);


        return view('/user_page')->with([
            'users' => $users
        ]);
    }

//ユーザー別勤怠一覧の取得(user_list)
    public function listbyUser(Request $request)
    {
        $id = $request->input('id');
        $user = User::find($id);
        $name = $user -> name;
        $userId= $user -> id;
        $resultArray[] = array();
        $i = 0;

        $userAttendanceAll = Attendance::where('user_id', $userId)->get();
        Attendance::where('date', $userId)->get();
        foreach ($userAttendanceAll as $userAttendance) {
            if ($userAttendance->end_time) {
                $userRestAll = Rest::where(
                    'attendance_id',
                    $userAttendance->id
                )->get();

                $restTimeDiffInSecondsTotal = 0;
        foreach ($userRestAll as $userRest) {
                    $restTime = $this->calculateRestTime($userRest);
                    $restTimeDiffInSecondsTotal += $restTime;
                }

                $result = $this->actualWorkTime($userAttendance, $restTimeDiffInSecondsTotal);
                $resultArray[$i] = $result;
                $i++;
            }
        }

        $attendances = $this->paginate(
            $resultArray,
            5,
            null,
            ['path' => "/user_list?id={$id}"]
        );

        return view('/user_list')->with([
            'attendances' => $attendances,
            'user'  => $user,
        ]);
    }
}