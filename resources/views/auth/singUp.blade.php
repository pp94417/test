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

        <form action="/user/auth/sing-up" method="post">

            <!-- 手動加入 csrf_token 隱藏欄位，欄位變數名稱為 _token -->
            <input type="hidden" name="_token" value="{{ csrf_token() }}">

            <lable>
                暱稱：
                <input type="text"
                    name="nickname"
                    placeholder="暱稱"
                    value="{{ old('nickname') }}"
                >
            </lable>
            <lable>
                Email：
                <input type="text"
                    name="email"
                    placeholder="Email"
                    value="{{ old('email') }}"
                >
            </label>
            <lable>
                密碼：
                <input type="password"
                    name="password"
                    placeholder="密碼"
                >
            </lable>
            <lable>
                確認密碼：
                <input type="password"
                    name="password_confirmation"
                    placeholder="確認密碼"
                >
            </lable>
            <lable>
                帳號類型：
                <select name="type">
                    <option value="G">一般會員</option>
                    <option value="A">管理員</option>
                </select>
            </lable>

            <button type="submit">註冊</button>
        </from>
    </div>
    
@endsection