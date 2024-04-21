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




  <!-- <div class="date">
    <form action="/user_list" method="get">
      @csrf
      <button name="date_n" id="prev__n" value=""> &lt;
      </button>
    </form>
    <div class="date__n">
      <p class="date__today">
      </p>
    </div>
    <form action="/user_list" method="get">
      @csrf
      <button name="date_n" id="next__n" value=""> &gt;
      </button>
    </form>
  </div> -->

  <!-- <div class="month_chenge">
    <form action="/user_list" method="get">
      <label for="time">月の変更：</label>
      <select name=" month" id="selectMonth">
        <option value="">-</option>
        <option value="1">1</option>
        <option value="2">2</option>
        <option value="3">3</option>
        <option value="4">4</option>
        <option value="5">5</option>
        <option value="6">6</option>
        <option value="7">7</option>
        <option value="8">8</option>
        <option value="9">9</option>
        <option value="10">10</option>
        <option value="11">11</option>
        <option value="12">12</option>
      </select>月
      <input type="submit" value="変更">
    </form>
  </div> -->
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

