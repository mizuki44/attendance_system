@extends('layouts.default')


<head>
  <link rel="stylesheet" href="/css/default.css">
  <link rel="stylesheet" href="/css/user_page.css">
  <link rel="stylesheet" href="/css/sanitize.css">


</head>
@section('title', 'ユーザー一覧')
@section('content')



<main>
  <div class="user-title">
    <h1>ユーザー一覧</h1>
  </div>
  <div class="result user-list">
    <table class="result-table user-table">
      <tr class="table-title">
        <th>名前</th>
        <th>勤怠記録</th>
      </tr>
      @foreach ($users as $user)
      <form action="/user_list" method="get">
        <tr class="table-value table-value-user">
          <td>
            {{ $user->name }}
          </td>
          <td>
            <button onclick="location.href=''">勤怠一覧</button>
          </td>
        </tr>
        <input type="hidden" name="id" value="{{$user->id}}">
      </form>
      @endforeach
    </table>
  </div>
  <div class="paginate">
    {{ $users->links() }}
  </div>
</main>
@endsection