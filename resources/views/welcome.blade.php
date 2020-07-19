@extends('layouts.app')

@section('content')
    @if (Auth::check())
        {{ Auth::user()->name }}
        <div class="col-sm-8">
            {{-- タスク一覧 --}}
            @include('tasks.index')
        </div>
    @else
        <div class="center jumbotron">
            <div class="text-center">
                <h1>タスク管理アプリ</h1>
                {{-- ユーザ登録ページへのリンク --}}
                {!! link_to_route('signup.get', '新規登録', [], ['class' => 'btn btn-lg btn-success']) !!}
            </div>
        </div>
    @endif
@endsection