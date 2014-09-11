@extends(Helper::layout())


@section('style')
@stop


@section('content')

    
        <main class="index-page">
            <section class="main-slide slide-welcome">
                <div class="slide-block">
                    <h1>Добро пожаловать!</h1>
                    <p>
                        Раз вы попали на наш сайт, значит вы ищите услуги по ремонту одежды. Сейчас мы вам расскажем, почему нужно обратится именно к нам!
                    </p>
                </div>
                <a href="#" class="slide-link"></a>
            </section>

            <section class="main-slide slide-service">
                <div class="slide-block">
                    <h1>Особая услуга выезда мастера в офис</h1>
                    <p>
                        С каждым клиентом ведется индивидуальная работа. Всю необходимую фурнитуру и ткани/кожу для работы ателье подбирает самостоятельно, экономя время клиента на ее поиски.
                    </p>
                    <a href="#" class="spec-btn">Услуги</a>
                </div>
                <a href="#" class="slide-link"></a>
            </section>

            <section class="main-slide slide-partners">
                <div class="slide-block">
                    <h1>Особые условия для партнеров</h1>
                    <p>
                        Хотите идеально подогнать по фигуре купленную вещь? Или дать новую жизнь уже имеющимся вещам, которые стали сидеть по-другому?
                    </p>
                    <a href="#" class="spec-btn">Франчайзинг</a>
                </div>
                <a href="#" class="slide-link"></a>
            </section>

            <section class="main-slide slide-examples">
                <div class="slide-block">
                    <h1>Мы делаем больше, чем другие ателье</h1>
                    <p>
                        Хотите идеально подогнать по фигуре купленную вещь? Или дать новую жизнь уже имеющимся вещам, которые стали сидеть по-другому?
                    </p>
                    <a href="#" class="spec-btn">Примеры работ</a>
                </div>
                <a href="#" class="slide-link"></a>
            </section>

            <section class="main-slide slide-location">
                <div class="slide-block">
                    <h1>Мы совсем рядом</h1>
                    <p>
                        Наше ателье расположено в само сердце самого популярного ТРК Ростова-на-Дону – Мегацентр Горизонт.
                    </p>
                    <a href="#" class="spec-btn">Как нас найти</a>
                </div>
                <a href="#" class="slide-link"></a>
            </section>
        </main>

@stop


@section('footer')
@stop


@section('scripts')
        <script>
            $(document).main_slider($('.main-slide'));
        </script>
@stop
