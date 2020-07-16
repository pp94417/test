<!-- 指定繼承 layout.master 母模板 -->
@extends('layout.master')


<!-- 傳送資料到母模板，並指定變數為 title -->
@section('title', $title)


<!-- 傳送資料到母模板，並指定變數為 content -->
@section('content')
    <div class="container">
        <h1>{{ $title }}</h1>

        <!--錯誤訊息模板元件-->
        @include('components.validationErrorMessage')

        <form action="/user/auth/sing-in" method="post">

            <!-- 手動加入 csrf_token 隱藏欄位，欄位變數名稱為 _token -->
            <input type="hidden" name="_token" value="{{ csrf_token() }}">

            <label>
                Email：
                    <input type="text"
                        name="email"
                        placeholder="Email"
                        value="{{old('password')}}"
                    >
            </label>

            <label>
                密碼：
                <input type="password"
                    name="password"
                    placeholder="密碼"
                    value="{{old('password')}}"
                >
            </label>

            <button type="submit">登入</button>
        </form>
    </div>
@endsection