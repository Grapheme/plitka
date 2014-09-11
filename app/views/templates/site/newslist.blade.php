@extends(Helper::layout())


@section('style')
@stop


@section('content')



        <main class="normal-page">
            <div class="wrapper">
                <div class="page-title news-title">
                    <div class="title-img"></div>
                    <h1>Новости</h1>
                </div>
                <div class="page-content">

                    {{ $page->block('newslist') }}

                </div>
            </div>
        </main>

@stop


@section('footer')
@stop


@section('scripts')
<script>
    $('.js-tabs').tabs();
</script>
@stop
