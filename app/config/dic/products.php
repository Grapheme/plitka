<?php

return array(

    'fields' => function () {

        $dics_slugs = array(
            'collections',
            'colors',
            'surface_type',
            'format',
        );
        $dics = Dic::whereIn('slug', $dics_slugs)->with('values')->get();
        $dics = Dic::modifyKeys($dics, 'slug');
        #Helper::tad($dics);
        $lists = Dic::makeLists($dics, 'values', 'name', 'id');
        #Helper::dd($lists);

        return array(

            'collection_id' => array(
                'title' => 'Коллекция',
                'type' => 'select',
                'values' => array('Выберите..') + $lists['collections'],
                'default' => Input::get('filter.fields.collection_id'),
            ),

            'article' => array(
                'title' => 'Артикул',
                'type' => 'text',
            ),

            'image_id' => array(
                'title' => 'Фотография',
                'type' => 'image',
            ),

            'format_id' => array(
                'title' => 'Формат',
                'type' => 'select',
                'values' => array('Выберите..') + $lists['format'],
            ),

            'size_text' => array(
                'title' => 'Размер',
                'type' => 'text',
            ),

            'package_text' => array(
                'title' => 'Упаковка',
                'type' => 'text',
            ),

            'color_id' => array(
                'title' => 'Цвет',
                'type' => 'select',
                'values' => array('Выберите..') + $lists['colors'],
            ),

            'surface_type_id' => array(
                'title' => 'Тип поверхности',
                'type' => 'select',
                'values' => array('Выберите..') + $lists['surface_type'],
            ),

            'price' => array(
                'title' => 'Цена',
                'type' => 'text',
                'others' => array(
                    #'maxlength' => 5,
                    #'onkeyup' => "this.value = this.value.replace (/\D/, '')", ## ONLY DIGITS
                ),
            ),

            'basic' => array(
                'no_label' => true,
                'title' => 'Базовая продукция',
                'type' => 'checkbox',
                'label_class' => 'normal_checkbox',
            ),
        );

    },


    'menus' => function($dic, $dicval = NULL) {
        $menus = array();
        $menus[] = array('raw' => '<br/>');

        $dics_slugs = array(
            'collections',
        );
        $dics = Dic::whereIn('slug', $dics_slugs)->with('values')->get();
        $dics = Dic::modifyKeys($dics, 'slug');
        $lists = Dic::makeLists($dics, 'values', 'name', 'id');
        #Helper::tad($lists);

        $menus[] = Helper::getDicValMenuDropdown('collection_id', 'Все коллекции', $lists['collections'], $dic);
        return $menus;
    },


    'actions' => function($dic, $dicval) {
    },


    'hooks' => array(

        'before_all' => function ($dic) {
        },

        'before_index' => function ($dic) {
        },

        'before_index_view' => function ($dic, $dicvals) {
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
