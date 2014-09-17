<?php

return array(

    'fields' => function () {

        $dics_slugs = array(
            'collections',
            'colors',
            'surface',
        );
        $dics = Dic::whereIn('slug', $dics_slugs)->with('values')->get();
        $dics = Dic::modifyKeys($dics, 'slug');
        #Helper::tad($dics);
        $lists = Dic::makeLists($dics, 'values', 'name', 'id');
        #Helper::dd($lists);

        return array(

            'image_id' => array(
                'title' => 'Фотография',
                'type' => 'image',
            ),

            'collection_id' => array(
                'title' => 'Коллекция',
                'type' => 'select',
                'values' => array('Выберите..') + $lists['collections'],
            ),

            'color_id' => array(
                'title' => 'Цвет',
                'type' => 'select',
                'values' => array('Выберите..') + $lists['colors'],
            ),

            'surface_id' => array(
                'title' => 'Поверхность',
                'type' => 'select',
                'values' => array('Выберите..') + $lists['surface'],
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