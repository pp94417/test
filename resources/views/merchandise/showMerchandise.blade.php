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

        <table>
            <tr>
                <th>名稱</th>
                <td>{{$Merchandise->name}}</td>
            </tr>
            <tr>
                <th>照片</th>
                <td>
                    <img src="{{$Merchandise->photo or '/assets/images/default-merchandise.png'}}" />
                </td>
            </tr>
            <tr>
                <th>價格</th>
                <td>{{$Merchandise->price}}</td>
            </tr>
            <tr>
                <th>剩餘數量</th>
                <td>{{$Merchandise->remain_count}}</td>
            </tr>
            <tr>
                <th>介紹</th>
                <td>{{$Merchandise->introduction}}</td>
            </tr>
            <tr>
                <td colspan="2">
                    <form action="/merchandise/{{$Merchandise->id}}/buy" moth="post">
                        購買數量
                        <select name="buy_count">
                            @for($count = 0; $count <= $Merchandise->remain_count; $count++)
                                <option value="{{$count}}">{{$count}}</option>
                            @endfor
                        </select>
                        <button tybe="submit">購買</button>
                        {{csrf_field()}}
                    </from>
                </td>
            </tr>
        </table>
        <li><a href="/transaction">交易紀錄</a></li>
    </div>
@endsection