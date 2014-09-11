@extends(Helper::layout())


@section('style')
@stop


@section('content')

    
        <main class="normal-page">

            {{ $page->block('content') }}

        </main>

@stop




@section('scripts')
@stop
