<?php

return array(

    'fields' => function () {

        $dics_slugs = array(
            'countries',
        );
        $dics = Dic::whereIn('slug', $dics_slugs)->with('values')->get();
        $dics = Dic::modifyKeys($dics, 'slug');
        #Helper::tad($dics);
        $lists = Dic::makeLists($dics, 'values', 'name', 'id');
        #Helper::dd($lists);

        return array(

            'country_id' => array(
                'title' => 'Страна',
                'type' => 'select',
                'values' => array('Выберите..') + $lists['countries'],
                'default' => Input::get('filter.fields.country_id'),
            ),
        );

    },


    'menus' => function($dic, $dicval = NULL) {
        #/*
        $menus = array();
        $menus[] = array('raw' => '<br/>');

        $dics_slugs = array(
            'countries',
        );
        $dics = Dic::whereIn('slug', $dics_slugs)->with('values')->get();
        $dics = Dic::modifyKeys($dics, 'slug');
        $lists = Dic::makeLists($dics, 'values', 'name', 'id');
        #Helper::tad($lists);

        $menus[] = Helper::getDicValMenuDropdown('country_id', 'Все страны', $lists['countries'], $dic);
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
            /**
             * Предзагружаем нужные словари
             */
            $dics_slugs = array(
                'countries',
            );
            $dics = Dic::whereIn('slug', $dics_slugs)->with('values')->get();
            $dics = Dic::modifyKeys($dics, 'slug');
            #Helper::tad($dics);
            #Config::set('temp.index_dics', $dics);

            $dics_values = Dic::makeLists($dics, 'values', 'name', 'id');
            Config::set('temp.index_countries', $dics_values['countries']);
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

    /**
     * Если данная функция объявлена, то ее вывод заменит вторую строку в списке записей словаря
     */
    'second_line_modifier' => function($line, $dic, $dicval) {
        #Helper::tad($dicval);

        $countries = Config::get('temp.index_countries');
        #Helper::d($countries);
        $country = @$countries[$dicval->country_id];
        return $country;
    },

    'seo' => false,
);
