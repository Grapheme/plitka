<?php

return array(

    'fields' => function () {

        return array(

            'image_id' => array(
                'title' => 'Изображение',
                'type' => 'image',
            ),
            'link' => array(
                'title' => 'Ссылка',
                'type' => 'text',
            ),
        );

    },


    'menus' => function($dic, $dicval = NULL) {

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
