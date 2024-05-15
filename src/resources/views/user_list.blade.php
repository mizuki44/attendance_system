@extends('layouts.default')


<head>
  <link rel="stylesheet" href="/css/default.css">
  <link rel="stylesheet" href="/css/user_list.css">
  <link rel="stylesheet" href="/css/sanitize.css">


</head>
@section('title', 'ユーザー別勤怠ページ')
@section('content')

<main>

<main>
  <div class="user__name">
    @if(Auth::check())
    <p>{{$user->name}}さんの勤怠記録</p>
    @endif
  </div>


  <div class="list">

    <table class="attendance_list">
      <tr class="table-title">
        <th>日付</th>
        <th>勤務開始</th>
        <th>勤務終了</th>
        <th>勤務時間</th>
        <th>休憩時間</th>
      </tr>

      @foreach($attendances as $attendance)
      <form action="/user_list" method="get">
        <tr class="table-value table-value-info">
          @if(!empty($attendance))
          <td>{{$attendance['date']}}</td>
          <td>{{$attendance['attendanceStartTime']}}</td>
          <td>{{$attendance['attendanceEndTime']}}</td>
          <td>{{$attendance['actualWorkTime']}}</td>
          <td>{{$attendance['restTime']}}</td>
          @endif
        </tr>
      </form>

      @endforeach

    </table>

    <div class="paginate">
      <form action="/user_list?name={$name}" method="get">
        <input type="hidden" name="date" value="date">
        {{ $attendances->links() }}
      </form>
    </div>

  </div>
  
</main>
@endsection

