<?php

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

            #/*
            'show_on_mainpage' => array(
                'title' => 'Выводить на главной',
                'type' => 'checkbox',
                'no_label' => true,
                'label_class' => 'normal_checkbox',
            ),
            #*/

            'description' => array(
                'title' => 'Описание',
                'type' => 'textarea',
            ),

            'product_type_id' => array(
                'title' => 'Вид продукции',
                'type' => 'select',
                'values' => array('Выберите..') + $lists['product_type'],
                'default' => Input::get('filter.fields.product_type_id'),
            ),

            'country_id' => array(
                'title' => 'Страна',
                'type' => 'select',
                'values' => array('Выберите..') + $lists['countries'],
                'default' => Input::get('filter.fields.country_id'),
            ),

            'factory_id' => array(
                'title' => 'Фабрика',
                'type' => 'select',
                'values' => array('Выберите..') + $lists['factory'],
                'default' => Input::get('filter.fields.factory_id'),
            ),

            array('content' => '<hr/>'),

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
            ),

            array('content' => '<hr/>'),

            'gallery_id' => array(
                'title' => 'Интерьеры',
                'type' => 'gallery',
                'params' => array(
                    'maxfilesize' => 1, // MB
                    #'acceptedfiles' => 'image/*',
                ),
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
            'product_type',
            'countries',
            'factory',
        );
        $dics = Dic::whereIn('slug', $dics_slugs)->with('values')->get();
        $dics = Dic::modifyKeys($dics, 'slug');

        ## Выбираем только те фабрики, которые соответствуют заданой стране
        if ($dics['factory']) {
            $factories = DicVal::extracts($dics['factory']->values, 1);
            foreach ($factories as $f => $factory) {
                if ($factory->country_id != Input::get('filter.fields.country_id'))
                    unset($factories[$f]);
            }
            #Helper::ta($factories);
            $dics['factory']->values = $factories;
        }
        $lists = Dic::makeLists($dics, 'values', 'name', 'id');
        #Helper::tad($lists);

        #/*
        $menus[] = Helper::getDicValMenuDropdown('product_type_id', 'Все виды продукции', $lists['product_type'], $dic);
        $menus[] = Helper::getDicValMenuDropdown('country_id', 'Все страны', $lists['countries'], $dic);
        if (Input::get('filter.fields.country_id') && count($lists['factory']))
            $menus[] = Helper::getDicValMenuDropdown('factory_id', 'Все фабрики', $lists['factory'], $dic);
        #*/
        #$menus[] = Helper::getDicValMenuDropdown('format_id', 'Все форматы', 'format', $dic);
        return $menus;
    },


    'actions' => function($dic, $dicval) {
        ## Data from hook: before_index_view
        $dics = Config::get('temp.index_dics');
        $dic_products = $dics['products'];
        #$dic_interiors = $dics['interiors'];
        $counts = Config::get('temp.index_counts');
        return '
            <span class="block_ margin-bottom-5_">
                <a href="' . URL::route('entity.index', array('products', 'filter[fields][collection_id]' => $dicval->id)) . '" class="btn btn-default">
                    Продукция (' . @(int)$counts[$dicval->id][$dic_products->id] . ')
                </a>
            </span>
                '
            /*
            . '
                <a href="' . URL::route('entity.index', array('interiors', 'filter[fields][collection_id]' => $dicval->id)) . '" class="btn btn-default">
                    Интерьеры (' . @(int)$counts[$dicval->id][$dic_interiors->id] . ')
                </a>
            </span>
            '*/
            ;
    },

    'hooks' => array(

        'before_all' => function ($dic) {
        },

        'before_index' => function ($dic) {
        },

        'before_index_view' => function ($dic, $dicvals) {
            $dics_slugs = array(
                'products',
                #'interiors',
            );
            $dics = Dic::whereIn('slug', $dics_slugs)->get();
            $dics = Dic::modifyKeys($dics, 'slug');
            #Helper::tad($dics);
            Config::set('temp.index_dics', $dics);

            $dic_ids = Dic::makeLists($dics, false, 'id');
            #Helper::d($dic_ids);
            $dicval_ids = Dic::makeLists($dicvals, false, 'id');
            #Helper::d($dicval_ids);

            $counts = array();
            if (count($dic_ids) && count($dicval_ids))
                $counts = DicVal::counts_by_fields($dic_ids, array('collection_id' => $dicval_ids));
            #Helper::dd($counts);
            Config::set('temp.index_counts', $counts);
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

    'first_line_modifier' => function($line, $dic, $dicval) {
        #Helper::ta($dicval);
        return $dicval->name;
    },

    'second_line_modifier' => function($line, $dic, $dicval) {
        #Helper::ta($dicval);
        return $dicval->slug;
    },


    'seo' => false,
);
