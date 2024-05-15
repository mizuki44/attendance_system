@extends('layouts.default')
<?php

use Carbon\Carbon;
?>

<head>
  <link rel="stylesheet" href="/css/sanitize.css">
  <link rel="stylesheet" href="/css/attendance_list.css">
</head>

@section('title', '日付一覧')
@section('content')

<main>
  <div class="date">
    <form action="/attendance_list" method="get">
      @csrf
      <button name="date" id="prev" value="{{ $yesterday->format('Y-m-d')}}"> &lt;
      </button>
    </form>

    <p class="date__today">
      {{ $today->format('Y-m-d') }}
    </p>

    <form action="/attendance_list" method="get">
      @csrf
      <button name="date" type="date" id="next" value="{{ $tomorrow->format('Y-m-d') }}"> &gt;</button>
    </form>

  </div>

  <div class="list">
    <table class="attendance_list">
      <tr class="table-title">
        <th>名前</th>
        <th>勤務開始</th>
        <th>勤務終了</th>
        <th>休憩時間</th>
        <th id="attendance_time">勤務時間</th>
      </tr>

      @foreach($attendances as $attendance)
      <form action="/getAttendance" method="get">
      @if(!empty($attendance))
        <tr class="table-value table-value-info">
          <td>{{$attendance['name']}}</td>
          <td>{{$attendance['attendanceStartTime']}}</td>
          <td>{{$attendance['attendanceEndTime']}}</td>
          <td>{{$attendance['restTime']}}</td>
          <td>{{$attendance['actualWorkTime']}}</td>
        </tr>
      @endif
      </form>
      @endforeach
    </table>
  </div>

  <div class="paginate">
    <form action="/attendance_list/{num}}" method="get">
      <input type="hidden" name="date" value="{{ $today->format('Y-m-d') }}">
      {{ $attendances->links() }}
    </form>
  </div>
  
</main>
@endsection