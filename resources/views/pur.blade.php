<!DOCTYPE html>
<head>
    <title>Pur</title>
    <meta charset="utf-8">
    <link href="{{ env('APPWEBPATH') }}/css/bootstrap-datetime.css" rel="stylesheet">
    <link href="{{ env('APPWEBPATH') }}/css/app.css" rel="stylesheet">
    <link href="{{ env('APPWEBPATH') }}/css/pur.css" rel="stylesheet">

    <!-- Fonts -->
    <link href="{{ env('APPWEBPATH') }}/css/font-awesome.min.css" rel="stylesheet">

</head>
<body>
<div class="loading"></div>
<nav class="navbar navbar-inverse">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                    data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle Navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">Pur</a>
        </div>

        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li><a href="/">Hjem</a></li>
                <li class="dropdown">
                    <a href="/" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Utforsk <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li>{!! link_to_route('brukere.show', 'Brukere', null) !!}</li>
                        <li>{!! link_to_route('oppgavesett.show', 'Oppgavesett', null) !!}</li>
                        <li>{!! link_to_route('bilagsmalsekvenser.show', 'Bilagsmalsekvenser', null) !!}</li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="/" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Mine
                        oppgaver <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li>{!! link_to_route('besvarelser.show', 'Mine oppgavesett', null) !!}</li>
                    </ul>
                </li>

            </ul>

            <ul class="nav navbar-nav navbar-right">
                @if (Auth::guest())
                    <li><a href="/auth/login"><span class="fa fa-sign-in"></span> Logg inn</a></li>
                    {{--<li><a href="/auth/register">Registrer</a></li>--}}
                @else
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                           aria-expanded="false">{{ Auth::user()->fulltNavn() }} <span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="/auth/logout"><span class="fa fa-sign-in"></span> Logg ut</a></li>
                        </ul>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>

    @yield('content')

<script src="{{ env('APPWEBPATH') }}/js/jquery-1.11.2.min.js"></script>
<script src="{{ env('APPWEBPATH') }}/js/bootstrap.min.js"></script>
<script src="{{ env('APPWEBPATH') }}/js/moment-with-locales.js"></script>
<script src="{{ env('APPWEBPATH') }}/js/bootstrap-datetime.js"></script>

<script src="{{ env('APPWEBPATH') }}/js/globalScripts.js"></script>
<script>


</script>
@yield('scripts')



</body>
</html>