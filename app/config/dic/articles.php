<?php

return array(

    'fields' => function () {

        $dics_slugs = array(
            'article_categories',
        );
        $dics = Dic::whereIn('slug', $dics_slugs)->with('values')->get();
        $dics = Dic::modifyKeys($dics, 'slug');
        #Helper::tad($dics);
        $lists = Dic::makeLists($dics, 'values', 'name', 'id');
        #Helper::dd($lists);

        return array(

            'publiched_at' => array(
                'title' => 'Дата публикации',
                'type' => 'date',
                'others' => array(
                    'class' => 'text-center',
                    'style' => 'width: 221px',
                    'placeholder' => 'Нажмите для выбора'
                ),
                'handler' => function($value) {
                    return $value ? @date('Y-m-d', strtotime($value)) : $value;
                },
                'value_modifier' => function($value) {
                    return $value ? date('d.m.Y', strtotime($value)) : date('d.m.Y');
                },
            ),

            'image_id' => array(
                'title' => 'Изображение',
                'type' => 'image',
            ),

            'category_id' => array(
                'title' => 'Раздел',
                'type' => 'select',
                'values' => array('Выберите..') + $lists['article_categories'],
            ),

            'content' => array(
                'title' => 'Содержимое статьи',
                'type' => 'textarea_redactor',
            ),
        );

    },


    'menus' => function($dic, $dicval = NULL) {
        #/*
        $menus = array();
        $menus[] = array('raw' => '<br/>');

        $dics_slugs = array(
            'article_categories',
        );
        $dics = Dic::whereIn('slug', $dics_slugs)->with('values')->get();
        $dics = Dic::modifyKeys($dics, 'slug');
        $lists = Dic::makeLists($dics, 'values', 'name', 'id');
        #Helper::tad($lists);

        $menus[] = Helper::getDicValMenuDropdown('collection_id', 'Все категории', $lists['article_categories'], $dic);
        return $menus;
        #*/
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
