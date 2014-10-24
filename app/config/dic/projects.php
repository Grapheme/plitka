<?php

return array(

    'fields' => function () {

        $dics_slugs = array(
            'collections',
        );
        $dics = Dic::whereIn('slug', $dics_slugs)->with('values')->get();
        $dics = Dic::modifyKeys($dics, 'slug');
        #Helper::tad($dics);
        $lists = Dic::makeLists($dics, 'values', 'name', 'id');
        #Helper::dd($lists);

        return array(

            'product_id' => array(
                'title' => 'Коллекция',
                'type' => 'select',
                'values' => array('Выберите..') + $lists['collections'],
            ),

            'description' => array(
                'title' => 'Описание',
                'type' => 'textarea_redactor',
            ),

            'image_id' => array(
                'title' => 'Основное изображение',
                'type' => 'image',
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
        );

    },


    'menus' => function($dic, $dicval = NULL) {
        /*
        $menus = array();
        $menus[] = array('raw' => '<br/>');

        $dics_slugs = array(
            'products',
        );
        $dics = Dic::whereIn('slug', $dics_slugs)->with('values')->get();
        $dics = Dic::modifyKeys($dics, 'slug');
        $lists = Dic::makeLists($dics, 'values', 'name', 'id');
        #Helper::tad($lists);

        $menus[] = Helper::getDicValMenuDropdown('collection_id', 'Вся продукция', $lists['products'], $dic);
        return $menus;
        */
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


    'seo' => 1,
);
