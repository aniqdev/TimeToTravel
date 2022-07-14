<link rel="stylesheet" type="text/css" href="{{ asset('css/app.css') }}" >

  <nav class="navbar navbar-dark bg-primary sticky-top">
    <div class="container-fluid">
      <a class="navbar-brand" href="{{ route('main') }}">TimeToTravel <b>Admin</b></a>

      @guest
      <div class="d-flex flex-row-reverse bd-highlight">
        <a class="navbar-brand bd-highlight nav-menu" href="{{ route('register') }}">{{ __('messages.register') }}</a>
        <a class="navbar-brand bd-highlight nav-menu" href="{{ route('login') }}">{{ __('messages.login') }}</a>
      </div>
      @endguest

      @auth
      <div class="d-flex flex-row-reverse bd-highlight">
        <a class="navbar-brand bd-highlight nav-menu" href="{{ route('logout') }}">{{ __('messages.logout') }}</a>
        <a class="navbar-brand bd-highlight nav-menu" href="{{ route('settings') }}">{{ __('messages.settings') }}</a>
        <a class="navbar-brand bd-highlight nav-menu" href="{{ route('settings') }}">{{ Auth::user()->name }} {{ Auth::user()->surname }}</a>
      </div>
      @endauth
    </div>
</nav>