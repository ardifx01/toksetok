<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | Toksetok</title>
    <link rel="shortcut icon" href="{{asset('assets/images/R.png')}}" type="image/x-icon">
    <link rel="stylesheet" href="{{asset('assets/compiled/css/app.css')}}">
    <link rel="stylesheet" href="{{asset('assets/compiled/css/app-dark.css')}}">
    <link rel="stylesheet" href="{{asset('assets/compiled/css/iconly.css')}}">
    <link rel="stylesheet" href="{{asset('assets/extensions/@fortawesome/fontawesome-free/css/all.min.css')}}">
</head>

<body>
    <script src="{{asset('assets/static/js/initTheme.js')}}"></script>
    <div id="app">
        @include('layout.sidebar')
        <div id="main">
            @include('layout.header')
            <inc class="page-content">
                @yield('content')
            </inc>
            @include('layout.footer')
</body>

</html>