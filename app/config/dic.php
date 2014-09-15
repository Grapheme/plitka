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
 * - checkboxes (замена select-multiple)
 * - checkbox
 *
 * Типы полей, запланированных к разработке:
 * - select-multiple
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

    'fields' => array(

        'collections' => array(

            'general' => array(

                'description' => array(
                    'title' => 'Описание',
                    'type' => 'textarea',
                ),

                'product_type_id' => array(
                    'title' => 'Вид продукции',
                    'type' => 'select',
                    'values' => array('Выберите..')+Dic::valuesBySlug('product_type')->lists('name', 'id'),
                ),

                'country_id' => array(
                    'title' => 'Страна',
                    'type' => 'select',
                    'values' => array('Выберите..')+Dic::valuesBySlug('countries')->lists('name', 'id'),
                ),

                'factory_id' => array(
                    'title' => 'Фабрика',
                    'type' => 'select',
                    'values' => array('Выберите..')+Dic::valuesBySlug('factory')->lists('name', 'id'),
                ),

                array('content' => '<hr/>'),

                'format_id' => array(
                    'title' => 'Формат',
                    'type' => 'select',
                    'values' => array('Выберите..')+Dic::valuesBySlug('format')->lists('name', 'id'),
                ),

                'surface_id' => array(
                    'title' => 'Поверхность',
                    'type' => 'select',
                    'values' => array('Выберите..')+Dic::valuesBySlug('surface')->lists('name', 'id'),
                ),

                #/*
                'scope_id' => array(
                    'title' => 'Места применения',
                    'type' => 'checkboxes',
                    'columns' => 2,
                    'values' => Dic::valuesBySlug('scope')->lists('name', 'id'),
                    'handler' => function($value, $element) {
                            $value = (array)$value;
                            $element->relations()->sync($value);
                            return @count($value);
                        },
                    'value_modifier' => function($value, $element) {
                            $return = (is_object($element) && $element->id)
                                ? $element->relations()->get()->lists('name', 'id')
                                : $return = array()
                            ;
                            return $return;
                        },
                ),
                #*/
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

            ),
        ),

        'products' => array(

            'general' => array(

                'image_id' => array(
                    'title' => 'Фотография',
                    'type' => 'image',
                ),

                'color_id' => array(
                    'title' => 'Цвет',
                    'type' => 'select',
                    'values' => array('Выберите..')+Dic::valuesBySlug('colors')->lists('name', 'id'),
                ),

                'surface_id' => array(
                    'title' => 'Поверхность',
                    'type' => 'select',
                    'values' => array('Выберите..')+Dic::valuesBySlug('surface')->lists('name', 'id'),
                ),

                'price' => array(
                    'title' => 'Цена',
                    'type' => 'text',
                ),

                'basic' => array(
                    'no_label' => true,
                    'title' => 'Базовая продукция',
                    'type' => 'checkbox',
                    'label_class' => 'normal_checkbox',
                ),

            ),
        ),

        'interiors' => array(

            'general' => array(

                'collection_id' => array(
                    'title' => 'Коллекция',
                    'type' => 'select',
                    'values' => array('Выберите..')+Dic::valuesBySlug('collections')->lists('name', 'id'),
                ),
                'gallery_id' => array(
                    'title' => 'Фотографии',
                    'type' => 'gallery',
                    'handler' => function($array, $element) {
                            return ExtForm::process('gallery', array(
                                'module'  => 'dicval_meta',
                                'unit_id' => $element->id,
                                'gallery' => $array,
                                'single'  => true,
                            ));
                        }
                ),
            ),
        ),

        'articles' => array(

            'general' => array(

                'category_id' => array(
                    'title' => 'Раздел',
                    'type' => 'select',
                    'values' => array('Выберите..')+Dic::valuesBySlug('article_categories')->lists('name', 'id'),
                ),
                'content' => array(
                    'title' => 'Содержимое статьи',
                    'type' => 'textarea_redactor',
                ),
            ),
        ),

        'projects' => array(

            'general' => array(

                'product_id' => array(
                    'title' => 'Плитка',
                    'type' => 'select',
                    'values' => array('Выберите..')+Dic::valuesBySlug('products')->lists('name', 'id'),
                ),
                'gallery_id' => array(
                    'title' => 'Фотографии',
                    'type' => 'gallery',
                    'handler' => function($array, $element) {
                            return ExtForm::process('gallery', array(
                                'module'  => 'dicval_meta',
                                'unit_id' => $element->id,
                                'gallery' => $array,
                                'single'  => true,
                            ));
                        }
                ),
            ),
        ),

    ),

    'seo' => array(
        'number_type' => 0,
    ),
);
