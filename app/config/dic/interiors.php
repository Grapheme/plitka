<?php

return array(

    'fields' => function () {

        return array(

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
