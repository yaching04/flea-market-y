<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>coachtech フリマ</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    @yield('css')
</head>

<body>

    <!-- ヘッダー -->
    <header class="header">
        <div class="header-container">
            <div class="header-inner">

                <!-- ロゴ部分 -->
                <a href="{{ route('items.index') }}" class="logo">
                    <img src="{{ asset('images/logo.png') }}" alt="coachtech" class="logo-img">
                </a>

                <!-- 検索バー -->
                <div class="search">
                    <form action="{{ request()->routeIs('items.mylist') ? route('items.mylist') : route('items.index') }}" method="GET" class="search-form">
                        <div class="search-wrapper">
                            <input type="text" name="keyword" value="{{ $keyword ?? '' }}" placeholder="なにをお探しですか？" class="search-input">
                            <button type="submit" class="search-icon-btn">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- 右側ナビ -->
                <nav class="nav-right">

                    @guest
                    <!-- 未ログイン時 -->
                        <a href="{{ route('login') }}" class="nav-link">ログイン</a>
                    @else
                    <!-- ログイン時 -->
                    <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                    @csrf
                        <button type="submit" class="nav-link logout">ログアウト</button>
                    </form>
                    @endguest

                    <a href="{{ route('mypage.index') }}" class="nav-link">マイページ</a>
                    <a href="{{ route('items.create') }}" class="sell-btn">出品</a>
                </nav>

            </div>
        </div>
    </header>

    <!-- メインコンテンツ -->
    <main class="main-content">
        @yield('content')
    </main>

    @yield('js')

</body>

</html>
