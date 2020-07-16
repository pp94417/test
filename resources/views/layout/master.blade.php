<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>@yield('title') - Shop Laravel</title>
        <script src="http://cdn.bootcss.com/jquery/1.11.0/jquery.min.js" type="text/javascript"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href=" https://netdna.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css " rel="stylesheet" type="text/css" />
    </head>
    <body>
        <ul class="nav">
            @if(session()->has('user_id'))
                <li><a href="/user/auth/sing-out">登出</a></li>
            @else
                <li><a href="/user/auth/sing-in">登入</a></li>
                <li><a href="/user/auth/sing-up">註冊</a></li>
            @endif
        </ul>


        <div class="container">
            @yield('content')
        </div>


        <footer>
        <a href="#">聯絡我們</a>
        </footer>
    </body>
</html>