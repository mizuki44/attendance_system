@extends('layouts.default')


<head>
  <link rel="stylesheet" href="/css/sanitize.css">
  <link rel="stylesheet" href="css/index.css">
</head>

@section('title', '打刻ページ')
@section('content')



<main>
  <div class="main__title">
    @if(Auth::check())
    <p>{{$user->name}}さんお疲れ様です！</p>
    @endif
  </div>



  <div class="main__attendance">
    <div class="attendance__left">


      <!-- 勤務開始 -->
      @if($isWorkStarted)
      <form action="/workStart" method="POST" class="timestamp">
        @csrf
        <button disabled style="color:gray">勤務開始</button>
      </form>
      @else
      <form action="/workStart" method="POST" class="timestamp">
        @csrf
        <button class="button1">勤務開始</button>
      </form>
      @endif


      <!-- 休憩開始 -->
      <!-- 出勤開始してたら、かつ休憩開始してたら -->
      @if($isWorkStarted && $isRestStarted)
      <form action="/restStart" method="POST" class="timestamp">
        @csrf
        <button disabled style="color:gray">休憩開始</button>
      </form>
      <!-- 勤務終了してたら -->
      @elseif($isWorkEnded)
      <form action="/restStart" method="POST" class="timestamp">
        @csrf
        <button disabled style="color:gray">休憩開始</button>
      </form>
      <!-- 休憩終了してたら -->
        @elseif($isRestEnded)
      <form action="/restStart" method="POST" class="timestamp">
        @csrf
        <button class="button2">休憩開始</button>
      </form>
      <!-- 勤務開始してたら -->
      @elseif($isWorkStarted)
      <form action="/restStart" method="POST" class="timestamp">
        @csrf
        <button class="button2">休憩開始</button>
      </form>
      @else
      <form action="/restStart" method="POST" class="timestamp">
        @csrf
        <button disabled style="color:gray">休憩開始</button>
      </form>
      @endif
    </div>

    <div class="attendance__right">
      <!-- 勤務終了 -->
      @if($isWorkStarted && !$isWorkEnded)
      <form action="/workEnd" method="POST" class="timestamp">
        @csrf
        <button class="button3">勤務終了</button>
      </form>
      @elseif($isWorkStarted && $isWorkEnded)
      <form action="/workEnd" method="POST" class="timestamp">
        @csrf
        <button disabled style="color:gray">勤務終了</button>
      </form>
      @else
      <form action="/workEnd" method="POST" class="timestamp">
        @csrf
        <button disabled style="color:gray">勤務終了</button>
      </form>
      @endif

      
      <!-- 休憩終了 -->
      @if(($isWorkStarted) && ($isRestStarted))
      <form action="/restEnd" method="POST" class="timestamp">
        @csrf
        <button class="button4">休憩終了</button>
      </form>
      @else
      <form action="/restEnd" method="POST" class="timestamp">
        @csrf
        <button disabled style="color:gray">休憩終了</button>
      </form>
      @endif



    </div>
  </div>
</main>
@endsection