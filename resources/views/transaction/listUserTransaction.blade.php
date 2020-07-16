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

        <table class="table">
            <tr>
    
                <th>單價</th>
                <th>數量</th>
                <th>總金額</th>
                <th>購買時間</th>>
            </tr>
            @foreach($TransactionPaginate as $Transaction)
            <tr>
       
                <td>{{$Transaction->price}}</td>
                <td>{{$Transaction->buy_count}}</td>
                <td>{{$Transaction->total_price}}</td>
                <td>{{$Transaction->created_at}}</td>
            </tr>
            @endforeach
        </table>
        <li><a href="/merchandise">商品檢視清單</a></li>
        {{$TransactionPaginate->links()}}

    </div>
@endsection