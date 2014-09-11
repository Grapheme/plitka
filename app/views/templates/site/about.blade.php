@extends(Helper::layout())


@section('style')
@stop


@section('content')

    
        <main class="normal-page">

            {{ $page->block('top') }}

            <div class="slider-container">
                <div class="slider-border">
                    <div class="photo-slider">

                        {{ $page->block('slider') }}

                    </div>
                </div>
                <div class="fotorama-nav">
                    <span></span> из <span></span>
                </div>
                <a href="#" class="prev"></a>
                <a href="#" class="next"></a>
            </div>

            {{ $page->block('desc') }}

        </main>

@stop





@section('scripts')
    <script>
        $('.photo-slider').custom_fotorama();
    </script>
@stop
