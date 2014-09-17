<?php
/**
 * С помощью данного конфига можно добавлять собственные поля к объектам DicVal.
 * Для каждого словаря (Dic) можно задать индивидуальный набор полей (ключ массива fields).
 * Набор полей для словаря определяется по его системному имени (slug).
 *
 * Для каждого словаря можно определить набор "постоянных" полей (general)
 * и полей для мультиязычных версий записи (i18n).
 * Первые будут доступны всегда, вторые - только если сайт имеет больше чем 1 язык.
 *
 * Каждое поле представлено в наборе именем на форме (ключ массива) и набором свойств (поля массива по ключу).
 * Обязательно должен быть определен тип поля (type) и заголовок (title).
 * Также можно задать следующие свойства:
 * - default - значение поля по-умолчанию
 * - others - набор дополнительных произвольных свойств элемента, таких как class, style, placeholder и т.д.
 * - handler - функция-замыкание, вызывается для обработки значения поля после получения ИЗ формы, перед записью в БД. Первым параметром передается значение поля, вторым - существующий объект DicVal, к которому относится данное поле
 * - value_modifier - функция-замыкание, вызывается для обработки значения поля после получения значения из БД, перед выводом В форму
 * - after_save_js - JS-код, который будет выполнен после сохранения страницы
 * - content - содержимое, которое будет выведено на экран, вместо генерации кода элемента формы
 * - label_class - css-класс родительского элемента
 *
 * Некоторые типы полей могут иметь свои собственные уникальные свойства, например: значения для выбора у поля select; accept для указания разрешенных форматов у поля типа file и т.д.
 *
 * [!] Вывод полей на форму происходит с помощью /app/lib/Helper.php -> Helper::formField();
 *
 * На данный момент доступны следующие поля:
 * - text
 * - textarea
 * - textarea_redactor (доп. JS)
 * - date (не требует доп. JS, работает для SmartAdmin из коробки, нужны handler и value_modifier для обработки)
 * - image (использует ExtForm::image() + доп. JS)
 * - gallery (использует ExtForm::gallery() + доп. JS, нужен handler для обработки)
 * - upload
 * - video
 * - select
 * - select-multiple
 * - checkbox
 * - checkboxes (замена select-multiple)
 *
 * Типы полей, запланированных к разработке:
 * - radio
 * - upload-group
 * - video-group
 *
 * Также в планах - возможность активировать SEO-модуль для каждого словаря по отдельности (ключ массива seo) и обрабатывать его.
 *
 * [!] Для визуального разделения можно использовать следующий элемент массива: array('content' => '<hr/>'),
 *
 * @author Zelensky Alexander
 *
 */
return array(

    'fields' => function () {

        $dics_slugs = array(
            'product_type',
            'countries',
            'factory',
            'format',
            'surface',
            'scope',
        );
        $dics = Dic::whereIn('slug', $dics_slugs)->with('values')->get();
        $dics = Dic::modifyKeys($dics, 'slug');
        #Helper::tad($dics);
        $lists = Dic::makeLists($dics, 'values', 'name', 'id');
        #Helper::dd($lists);

        return array(

            'description' => array(
                'title' => 'Описание',
                'type' => 'textarea',
            ),

            'product_type_id' => array(
                'title' => 'Вид продукции',
                'type' => 'select',
                #'values' => [],
                'values' => array('Выберите..') + $lists['product_type'],
            ),

            'country_id' => array(
                'title' => 'Страна',
                'type' => 'select',
                'values' => array('Выберите..') + $lists['countries'],
            ),

            'factory_id' => array(
                'title' => 'Фабрика',
                'type' => 'select',
                'values' => array('Выберите..') + $lists['factory'],
            ),

            array('content' => '<hr/>'),

            'format_id' => array(
                'title' => 'Формат',
                'type' => 'select',
                'values' => array('Выберите..') + $lists['format'],
            ),

            'surface_id' => array(
                'title' => 'Поверхность',
                'type' => 'select',
                'values' => array('Выберите..') + $lists['surface'],
            ),

            'scope_id' => array(
                'title' => 'Места применения',
                'type' => 'checkboxes',
                'columns' => 2,
                'values' => $lists['scope'],
                'handler' => function ($value, $element) {
                    $value = (array)$value;
                    $element->relations()->sync($value);
                    return @count($value);
                },
                'value_modifier' => function ($value, $element) {
                    $return = (is_object($element) && $element->id)
                        ? $element->relations()->get()->lists('name', 'id')
                        : $return = array();
                    return $return;
                },
            )
            /*
            'scope_id' => array(
                'title' => 'Места применения',
                'type' => 'select-multiple',
                'values' => Dic::valuesBySlug('scope')->lists('name', 'id'),
                'handler' => function($value, $element) {
                        $value = (array)$value;
                        $value = array_flip($value);
                        foreach ($value as $v => $null)
                            $value[$v] = array('dicval_child_dic' => 'scope');
                        $element->relations()->sync($value);
                        return @count($value);
                    },
                'value_modifier' => function($value, $element) {
                        $return = (is_object($element) && $element->id)
                            ? $element->relations()->get()->lists('id')
                            : $return = array()
                        ;
                        return $return;
                    },
            ),
            */
        );

    },


    'menus' => function($dic, $dicval = NULL) {
        $menus = array();
        $menus[] = array('raw' => '<br/>');
        /*
        $menus[] = Helper::getDicValMenuDropdown('product_type_id', 'Все виды продукции', 'product_type', $dic);
        $menus[] = Helper::getDicValMenuDropdown('country_id', 'Все страны', 'country', $dic);
        $menus[] = Helper::getDicValMenuDropdown('factory_id', 'Все фабрики', 'factory', $dic);
        */
        #$menus[] = Helper::getDicValMenuDropdown('format_id', 'Все форматы', 'format', $dic);
        return $menus;
    },


    'actions' => function($dic, $dicval) {
        return '
            <span class="block_ margin-bottom-5_">
                <a href="' . URL::route('entity.index', array('products', 'filter[fields][collection_id]' => $dicval->id)) . '" class="btn btn-default">
                    Продукция
                </a>
                <a href="' . URL::route('entity.index', array('interiors', 'filter[fields][collection_id]' => $dicval->id)) . '" class="btn btn-default">
                    Интерьеры
                </a>
            </span>
        ';
    },

    'hooks' => array(

        'before_index' => function ($dic) {
        },
        'before_index_view' => function ($dic) {
        },

        'before_create_edit' => function ($dic) {
        },
        'before_create' => function ($dic) {
        },
        'before_edit' => function ($dic, $dicval) {
        },

        'before_store_update' => function ($dic) {
        },
        'before_store' => function ($dic) {
        },
        'before_update' => function ($dic, $dicval) {
        },

        'before_destroy' => function ($dic, $dicval) {
        },
    ),

    'seo' => false,
);
