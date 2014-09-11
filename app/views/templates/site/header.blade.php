
        <header class="main-header index-header">
            <div class="wrapper">
                <a href="{{ URL::to('/') }}" class="min-logo"></a>
                <a href="{{ URL::to('/') }}" class="logo"></a>
                <div class="head-cont">
                    <h1 class="head-title">Профессиональный ремонт, реставрация и подгонка одежды по фигуре</h1>
                    <div class="head-contacts">
                        <div class="left-block">
                            Ростов-на-Дону, пр. М. Нагибина, 32/2,<br>
                            1 этаж, Мегацентр «Горизонт»
                        </div>
                        <div class="right-block">
                            <ul>
                                <li>
                                    Телефон:<br>
                                    <a class="head-bold" href="tel:+78633113543">(863) 311-35-43</a>

                                <li>
                                    Режим работы:<br>
                                    <span class="head-bold">10:00 – 22:00</span>
                            </ul>
                        </div>
                    </div>
                    <nav class="head-nav">
                        <ul>
                            <? #dd(Route::getCurrentRoute()->getParameter('url')); ?>
                            <li><a href="{{ URL::route('page', 'about') }}"{{ Helper::isRoute('page', 'about') }}>Об ателье</a>
                            <li><a href="{{ URL::route('page', 'service') }}"{{ Helper::isRoute('page', 'service') }}>Услуги</a>
                            <li><a href="{{ URL::route('page', 'examples') }}"{{ Helper::isRoute('page', 'examples') }}>Примеры работ</a>
                            <li><a href="{{ URL::route('page', 'advice') }}"{{ Helper::isRoute('page', 'advice') }}>Советы стилиста</a>
                            <li><a href="{{ URL::route('page', 'newslist') }}"{{ Helper::isRoute('page', 'newslist') }}>Новости</a>
                            <li><a href="{{ URL::route('page', 'contacts') }}"{{ Helper::isRoute('page', 'contacts') }}>Контакты</a>
                        </ul>
                    </nav>
                </div>
            </div>

            <!-- Mobile elements -->
            <div class="mobile-header">
                <a href="{{ URL::to('/') }}" class="logo"></a>
                <header>
                    <a href="#" class="menu-icon"><span></span></a>
                    <a href="{{ URL::to('/') }}" class="mobile-logo"></a>
                </header>
                <section class="mobile-menu">
                    <nav class="head-nav">
                        <ul>
                            <li><a href="{{ URL::route('page', 'about') }}">Об ателье</a>
                            <li><a href="{{ URL::route('page', 'service') }}">Услуги</a>
                            <li><a href="{{ URL::route('page', 'examples') }}">Примеры работ</a>
                            <li><a href="{{ URL::route('page', 'advice') }}">Советы стилиста</a>
                            <li><a href="{{ URL::route('news') }}">Новости</a>
                            <li><a href="{{ URL::route('page', 'contacts') }}">Контакты</a>
                        </ul>
                    </nav>
                    <div class="head-contacts">
                        <div class="left-block">
                            Ростов-на-Дону,<br>
                            пр. М. Нагибина, 32/2,<br>
                            1 этаж, Мегацентр «Горизонт»
                        </div>
                        <div class="right-block">
                            <ul>
                                <li>
                                    Телефон:<br>
                                    <a class="head-bold" href="tel:+78633113543">(863) 311-35-43</a>

                                <li>
                                    Режим работы:<br>
                                    <span class="head-bold">10:00 – 22:00</span>
                            </ul>
                        </div>
                    </div>
                </section>
            </div>
        </header>
