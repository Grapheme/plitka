@section('title')
{{{ isset($page_title) ? $page_title : Config::get('app.default_page_title') }}}
@stop
@section('description')
{{{ isset($page_description) ? $page_description : Config::get('app.default_page_description') }}}
@stop
@section('keywords')
{{{ isset($page_keywords) ? $page_keywords : Config::get('app.default_page_keywords') }}}
@stop
	    <title>@yield('title')</title>
        <meta name="description" content="@yield('description')">
        <meta name="keywords" content="@yield('keywords')">

        <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->

        {{ HTML::style('static/css/normalize.css') }}
        {{ HTML::style('static/css/main.css') }}
        {{ HTML::style('static/css/fotorama/fotorama.css') }}

        {{ HTML::script('static/js/vendor/modernizr-2.6.2.min.js') }}
